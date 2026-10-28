<?php

namespace App\Http\Controllers;

use App\Models\CallSession;
use App\Models\User;
use Illuminate\Http\Request;

class CallController extends Controller
{
    private function authorizeCall(CallSession $call): void
    {
        if (!in_array(auth()->id(), [$call->caller_id, $call->receiver_id])) {
            abort(403, 'UNAUTHORIZED CALL ACCESS.');
        }
        $otherId = (int) auth()->id() === (int) $call->caller_id ? $call->receiver_id : $call->caller_id;
        abort_unless(auth()->user()->canMessage(User::findOrFail($otherId)), 403, 'An accepted mentorship is required to call.');
    }

    public function start(Request $request, User $user)
    {
        abort_unless(auth()->user()->canMessage($user), 403, 'An accepted mentorship is required to call.');
        if (auth()->id() === $user->id) {
            abort(403, 'You cannot call yourself.');
        }

        if (!in_array($user->role, ['student', 'alumni'])) {
            abort(403, 'Only student and alumni calls are allowed.');
        }

        $request->validate([
            'type' => ['required', 'in:audio,video'],
        ]);

        if (CallSession::whereIn('status',['ringing','accepted'])->where('created_at','>=',now()->subMinutes(2))->where(fn ($q) => $q->whereIn('caller_id',[auth()->id(),$user->id])->orWhereIn('receiver_id',[auth()->id(),$user->id]))->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages(['call'=>'One of the participants is already on a call.']);
        }
        $call = CallSession::create([
            'caller_id' => auth()->id(),
            'receiver_id' => $user->id,
            'type' => $request->type,
            'status' => 'ringing',
            'caller_candidates' => [],
            'receiver_candidates' => [],
        ]);

        \App\Services\NotificationService::personal($user, 'call', 'Incoming '.ucfirst($request->type).' Call', auth()->user()->name.' is calling you.', route('calls.show', $call), 'high', $call, auth()->user());
        return redirect()->route('calls.show', $call);
    }

    public function incoming()
    {
        abort_unless(in_array(auth()->user()->role, ['student','alumni'], true), 403);
        $call = CallSession::with('caller')->where('receiver_id', auth()->id())->where('status','ringing')->where('created_at','>=',now()->subMinutes(2))->latest()->first();
        if (!$call || !$call->caller || !auth()->user()->canMessage($call->caller)) return response()->json(['call'=>null])->header('Cache-Control','no-store');
        return response()->json(['call'=>['id'=>$call->id,'name'=>$call->caller->name,'type'=>$call->type,'url'=>route('calls.show',$call),'reject_url'=>route('calls.reject',$call)]])->header('Cache-Control','no-store');
    }

    public function show(CallSession $call)
    {
        $this->authorizeCall($call);

        $otherUser = auth()->id() === $call->caller_id
            ? $call->receiver
            : $call->caller;

        return view('calls.show', compact('call', 'otherUser'));
    }

    public function accept(CallSession $call)
    {
        $this->authorizeCall($call);

        if (auth()->id() !== $call->receiver_id) {
            abort(403, 'Only receiver can accept the call.');
        }

        abort_unless($call->status === 'ringing' && $call->created_at->gte(now()->subMinutes(2)),409,'This call is no longer ringing.');
        $call->update([
            'status' => 'accepted',
        ]);

        return response()->json([
            'success' => true,
            'status' => $call->status,
        ]);
    }

    public function reject(CallSession $call)
    {
        $this->authorizeCall($call);

        if (auth()->id() !== $call->receiver_id) {
            abort(403, 'Only receiver can reject the call.');
        }

        abort_unless($call->status === 'ringing',409,'This call is no longer ringing.');
        $call->update([
            'status' => 'rejected',
            'ended_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'status' => $call->status,
        ]);
    }

    public function end(CallSession $call)
    {
        $this->authorizeCall($call);

        $call->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'status' => $call->status,
        ]);
    }

    public function storeOffer(Request $request, CallSession $call)
    {
        $this->authorizeCall($call);

        if (auth()->id() !== $call->caller_id) {
            abort(403, 'Only caller can create offer.');
        }

        $request->validate([
            'offer' => ['required'],
        ]);

        $call->update([
            'offer' => json_encode($request->offer),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function storeAnswer(Request $request, CallSession $call)
    {
        $this->authorizeCall($call);

        if (auth()->id() !== $call->receiver_id) {
            abort(403, 'Only receiver can create answer.');
        }

        $request->validate([
            'answer' => ['required'],
        ]);

        abort_unless(in_array($call->status, ['ringing','accepted'], true),409);
        $call->update([
            'answer' => json_encode($request->answer),
            'status' => 'accepted',
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function storeCandidate(Request $request, CallSession $call)
    {
        $this->authorizeCall($call);

        $request->validate([
            'candidate' => ['required'],
        ]);

        if (auth()->id() === $call->caller_id) {
            $candidates = $call->caller_candidates ?? [];
            $candidates[] = $request->candidate;

            $call->update([
                'caller_candidates' => $candidates,
            ]);
        } else {
            $candidates = $call->receiver_candidates ?? [];
            $candidates[] = $request->candidate;

            $call->update([
                'receiver_candidates' => $candidates,
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function poll(CallSession $call)
    {
        $this->authorizeCall($call);

        $call->refresh();

        return response()->json([
            'id' => $call->id,
            'type' => $call->type,
            'status' => $call->status,
            'offer' => $call->offer ? json_decode($call->offer, true) : null,
            'answer' => $call->answer ? json_decode($call->answer, true) : null,
            'caller_candidates' => $call->caller_candidates ?? [],
            'receiver_candidates' => $call->receiver_candidates ?? [],
            'caller_id' => $call->caller_id,
            'receiver_id' => $call->receiver_id,
            'auth_id' => auth()->id(),
        ]);
    }
}
