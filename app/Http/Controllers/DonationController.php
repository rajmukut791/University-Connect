<?php

namespace App\Http\Controllers;

use App\Services\EncryptedFileService;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    public function index()
    {
        if (Schema::hasTable('donations')) {
            $query = Donation::with(['user'])
                ->where(function ($query) {
                    if (!auth()->user()->isAdmin()) {
                        $query->where('status', 'approved')->orWhere('user_id', auth()->id());
                    }
                })
                ->when(Schema::hasColumn('donations', 'archived_at'), fn ($q) => $q->whereNull('archived_at'));
            $stats = ['total'=>(clone $query)->count(), 'approved'=>(clone $query)->where('status','approved')->count(), 'pending'=>(clone $query)->where('status','pending')->count()];
            if (auth()->user()->isAdmin() && in_array(request('status'), ['pending','approved','rejected'], true)) $query->where('status',request('status'));
            $donations = $query->latest()->orderByDesc('id')->paginate(6)->withQueryString();
        } else {
            $stats = ['total'=>0, 'approved'=>0, 'pending'=>0];
            $donations = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 6, request()->integer('page', 1), ['path'=>request()->url()]);
        }
        return view('donations.index', compact('donations', 'stats'));
    }

    public function create()
    {
        return view('donations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
                'bkash_number' => ['nullable','string','regex:/^(?:\+?88)?01[3-9][0-9]{8}$/'],
                'nagad_number' => ['nullable','string','regex:/^(?:\+?88)?01[3-9][0-9]{8}$/'],
            'target_amount' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string', 'max:3000'],
            'deadline' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

            if ($request->hasFile('image')) {
             $validated['image'] =
                app(EncryptedFileService::class)
             ->store(
                $request->file('image'),
                'donation-images'
             );
            }

        $validated['user_id'] = auth()->id();
        $validated['collected_amount'] = 0;
        $validated['status'] = auth()->user()->isAdmin() ? 'approved' : 'pending';

        Donation::create($validated);

        return redirect()
            ->route('donations.index')
            ->with('success', 'Donation campaign submitted successfully.');
    }

    public function show(Donation $donation)
    {
        abort_unless($donation->status === 'approved'
            || \App\Support\PostAccess::canManage(auth()->user(), 'donation', $donation), 404);
        $donation->load([
            'user',
            'contributions.user',
        ]);

        return view('donations.show', compact('donation'));
    }

    public function management(Donation $donation)
    {
        abort_unless(\App\Support\PostAccess::canManage(auth()->user(), 'donation', $donation), 403);
        $isOwner = (int) $donation->user_id === (int) auth()->id();
        $payments = \App\Models\DonationManualPayment::with('user', 'reviewer')->where('donation_id', $donation->id)->latest()->paginate(20, ['*'], 'payments_page')->withQueryString();
        $donors = \App\Models\DonationContribution::with('user')->where('donation_id', $donation->id)->where('status', 'confirmed')->latest()->paginate(20, ['*'], 'donors_page')->withQueryString();
        $cardPayments = \Illuminate\Support\Facades\DB::table('donation_card_payments')->leftJoin('users', 'users.id', '=', 'donation_card_payments.user_id')
            ->where('donation_card_payments.donation_id', $donation->id)->select('donation_card_payments.*', 'users.name as donor_name')->orderByDesc('donation_card_payments.created_at')->paginate(20, ['*'], 'cards_page')->withQueryString();
        return response()->view('donations.management', compact('donation', 'isOwner', 'payments', 'donors', 'cardPayments'))->header('Cache-Control', 'private, no-store');
    }

    public function approve(Donation $donation)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $donation->update(['status' => 'approved']);

        return back()->with('success', 'Donation campaign approved successfully.');
    }

    public function reject(Donation $donation)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $donation->update(['status' => 'rejected']);

        return back()->with('success', 'Donation campaign rejected successfully.');
    }

    public function destroy(Donation $donation)
    {
        return app(PostManagementController::class)->destroy('donation', $donation->id);
    }
}
