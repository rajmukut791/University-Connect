# University Connect production setup

Use `.env.production.example` as a checklist; never commit real keys or passwords. Existing local `.env` is intentionally unchanged.

1. Configure a reachable MySQL database. Back up the existing database before importing it. Use production credentials, APP_ENV=production and APP_DEBUG=false.
2. Import existing data and copy both `storage/app/public` and `storage/app/private` securely. Preserve the existing APP_KEY when moving encrypted files. Never expose the project root or private storage through the web server: its document root must be `public/`.
3. Use persistent storage for uploads. PRIVATE_STORAGE_PATH and PUBLIC_STORAGE_PATH can point to mounted persistent directories. A free ephemeral Render filesystem cannot preserve these files across restarts; hosting configuration or external persistent storage is still required.
4. Install dependencies and assets: `composer install --no-dev --optimize-autoloader`, `npm ci`, `npm run build`, `php artisan migrate --force`, `php artisan storage:link`, `php artisan optimize`.
5. Configure HTTPS, the real APP_URL and secure cookies. Ensure the server can write to storage and bootstrap/cache.
6. Schedule `php artisan schedule:run` every minute. Archive/temporary-block middleware also provides a cached fallback (at most once every 30 seconds per task) on web requests, but scheduled jobs should not depend on a visitor arriving. Login and authenticated access release an individual expired block immediately.
7. In each campaign, the owner must enter authorized bKash/Nagad receiving numbers through Edit/Update. Blank methods stay unavailable; no placeholder number is used.
8. Set Stripe keys and obtain STRIPE_WEBHOOK_SECRET from the real payment provider. Configure HTTPS POST `/payments/stripe/webhook` for checkout.session.completed and checkout.session.async_payment_succeeded. Card checkout remains unavailable until its verification configuration is present. Keep test and live payment credentials separate and verify a payment end-to-end before accepting money.
9. Set TURN_URL, TURN_USERNAME and TURN_PASSWORD using an authorized relay provider. The incoming-call notification/prompt is implemented, but real two-device calls must still be tested on different networks. Without TURN, STUN-only connections may fail behind restrictive networks.
10. Configure email delivery and verify Forgot Password. Back up the database and private/public uploads independently of deployment.

## Removed features
Ask AI, resume analysis, CV job matching and their dashboard suggestions/routes are removed. Historical migration tables and old uploaded data are retained without exposing them through these retired routes, to avoid destructive data loss. Normal profiles, interests, jobs, mentorships, donations, events, reports and messaging remain.


## Existing workflow navigation
- Students can open Alumni Conversion from their dashboard or Profile Settings; pending status and prior rejection feedback stay on the application page.
- Admin and Super Admin dashboards link to Pending Reviews, including Alumni Conversion approval, pending jobs/campaigns and per-section date extensions. The existing review pages enforce role access.
- The navbar bell opens the existing Notification Center for every signed-in role. The account dropdown remains Profile Settings, Archive/Requests and Logout.
- Conversations load the latest 50 messages first. Older/Newer history links preserve the permission checks and only displayed incoming messages are marked read.
- The feed fetches 20 posts per type per server page. Older/Newer posts access earlier batches; the existing interest ordering and local Load More apply within each batch.
