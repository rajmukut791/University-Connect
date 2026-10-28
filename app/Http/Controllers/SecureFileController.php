<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationManualPayment;
use App\Models\Event;
use App\Models\Message;
use App\Models\User;
use App\Services\EncryptedFileService;
use Illuminate\Http\Response;

class SecureFileController extends Controller
{
    public function __construct(
        private EncryptedFileService $files
    ) {
    }

    public function eventCover(Event $event): Response
    {
        $user = auth()->user();
        abort_unless($user && in_array($user->role, ['student', 'alumni', 'admin', 'super_admin'], true), 403);
        abort_if(empty($event->cover_image), 404);

        $path = $event->cover_image;
        if ($this->files->isEncryptedPath($path)) {
            abort_unless(\Illuminate\Support\Facades\Storage::disk('local')->exists($path), 404);
            $bytes = $this->files->get($path);
        } else {
            $disk = \Illuminate\Support\Facades\Storage::disk('public');
            abort_unless($disk->exists($path), 404);
            $bytes = $disk->get($path);
        }

        $image = @getimagesizefromstring($bytes);
        abort_unless($image && in_array($image['mime'], ['image/jpeg', 'image/png', 'image/webp'], true), 404);

        return response($bytes, 200, [
            'Content-Type' => $image['mime'],
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Image
    |--------------------------------------------------------------------------
    */

    public function profileImage(
        User $user
    ): Response {
        abort_unless(
            auth()->check(),
            403
        );

        abort_if(
            empty($user->profile_image),
            404
        );

        $path = $user->profile_image;
        if ($this->files->isEncryptedPath($path)) {
            abort_unless(\Illuminate\Support\Facades\Storage::disk('local')->exists($path), 404);
            $bytes = $this->files->get($path);
        } else {
            $disk = \Illuminate\Support\Facades\Storage::disk('public');
            abort_unless($disk->exists($path), 404);
            $bytes = $disk->get($path);
        }
        $image = @getimagesizefromstring($bytes);
        abort_unless($image && in_array($image['mime'], ['image/jpeg', 'image/png', 'image/webp'], true), 404);
        return response($bytes, 200, [
            'Content-Type' => $image['mime'],
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Donation Campaign Image
    |--------------------------------------------------------------------------
    */

    public function donationImage(
        Donation $donation
    ): Response {
        abort_unless(
            auth()->check(),
            403
        );

        abort_if(
            empty($donation->image),
            404
        );

        return $this->serve(
            $donation->image,
            'image/jpeg',
            'donation-image'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Donation Payment Screenshot
    |--------------------------------------------------------------------------
    */

    public function paymentScreenshot(
        DonationManualPayment $payment
    ): Response {
        $user = auth()->user();

        abort_unless(
            $user,
            403
        );

        $allowed =
            (int) $payment->user_id ===
                (int) $user->id
            ||
(int) $payment->donation?->user_id === (int) $user->id;

        abort_unless(
            $allowed,
            403
        );

        abort_if(
            empty($payment->screenshot),
            404
        );

        return $this->serve(
            $payment->screenshot,
            'image/jpeg',
            'payment-proof'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Message Attachment
    |--------------------------------------------------------------------------
    */

    public function messageAttachment(
        Message $message
    ): Response {
        $user = auth()->user();

        abort_unless(
            $user,
            403
        );

        $allowed =
            (int) $message->sender_id ===
                (int) $user->id
            ||
            (int) $message->recipient_id ===
                (int) $user->id;

        abort_unless(
            $allowed,
            403
        );

        abort_if(
            empty($message->attachment),
            404
        );

        $mime =
            $message->attachment_type
            ?: 'application/octet-stream';

        $name =
            $message->attachment_name
            ?: 'attachment';

        return $this->serve(
            $message->attachment,
            $mime,
            $name
        );
    }

/*
    |--------------------------------------------------------------------------
    | Serve / Decrypt
    |--------------------------------------------------------------------------
    */

    private function serve(
        string $path,
        string $mime,
        string $filename
    ): Response {
        /*
         * Temporary backward compatibility.
         *
         * Existing public files continue working
         * until migration is completed.
         */
        if (
            !$this->files
                ->isEncryptedPath($path)
        ) {
            abort_unless(
                \Storage::disk('public')
                    ->exists($path),
                404
            );

            $bytes =
                \Storage::disk('public')
                    ->get($path);

            return response(
                $bytes,
                200,
                [
                    'Content-Type' =>
                        $mime,

                    'Content-Disposition' =>
                        'inline; filename="' .
                        addslashes($filename) .
                        '"',

                    'Cache-Control' =>
                        'private, no-store, max-age=0',

                    'Pragma' =>
                        'no-cache',

                    'X-Content-Type-Options' =>
                        'nosniff',
                ]
            );
        }

        $bytes = $this->files->get(
            $path
        );

        return response(
            $bytes,
            200,
            [
                'Content-Type' =>
                    $mime,

                'Content-Disposition' =>
                    'inline; filename="' .
                    addslashes($filename) .
                    '"',

                'Cache-Control' =>
                    'private, no-store, max-age=0',

                'Pragma' =>
                    'no-cache',

                'X-Content-Type-Options' =>
                    'nosniff',
            ]
        );
    }
}
