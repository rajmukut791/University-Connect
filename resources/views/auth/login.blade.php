<x-guest-layout>
<style>
.uc-login { min-height: 100svh; background: radial-gradient(ellipse at 10% 20%, #102d50 0, transparent 45%), radial-gradient(ellipse at 90% 80%, #351248 0, transparent 50%), #020617; }
.uc-login:before { content: ''; position: absolute; inset: 0; pointer-events: none; opacity: .35; background: repeating-linear-gradient(0deg, transparent 0 53px, #38bdf818 54px), repeating-linear-gradient(90deg, transparent 0 53px, #818cf818 54px); animation: ucLoginGrid 18s linear infinite; }
.uc-login:after { content: ''; position: absolute; width: 45vmin; height: 45vmin; border: 1px solid #818cf844; border-radius: 50%; box-shadow: 0 0 70px #6366f130, inset 0 0 70px #22d3ee15; left: 12%; top: 24%; pointer-events: none; animation: ucLoginOrbit 12s ease-in-out infinite; }
.uc-login .max-w-6xl { gap: clamp(16px, 3vw, 40px); }
.uc-login h1 { font-size: clamp(2rem, 4vw, 3.5rem); }
.uc-login form.space-y-5 > :not([hidden]) ~ :not([hidden]) { margin-top: 12px; }
.uc-login .p-7, .uc-login .md\:p-9, .uc-login .p-8, .uc-login .p-10 { padding: clamp(16px, 2.2vh, 28px); }
.uc-login .mt-5, .uc-login .mt-6 { margin-top: 12px; }
.uc-login .mb-8 { margin-bottom: 16px; }
.uc-login .space-y-5 input { padding-top: 10px; padding-bottom: 10px; }
@keyframes ucLoginGrid { to { transform: translateY(54px); } }
@keyframes ucLoginOrbit { 50% { transform: translate(18px, -20px) scale(1.1); opacity: .5; } }


@media (prefers-reduced-motion: reduce) { .uc-login:before, .uc-login:after, .uc-login .animate-pulse { animation: none; } }

.uc-login { isolation: isolate; }
.uc-login:before { inset: -54px; mask-image: radial-gradient(ellipse, #000, transparent 85%); }
.uc-login:after { display: none; }
.uc-login-universe { position: absolute; inset: 0; overflow: hidden; pointer-events: none; }
.uc-login-aura { position: absolute; width: 65vmax; height: 65vmax; border-radius: 50%; filter: blur(65px); opacity: .22; background: radial-gradient(circle, #0891b2, transparent 65%); top: -35%; left: -20%; animation: ucAuraDrift 16s ease-in-out infinite alternate; }
.uc-login-aura.purple { background: radial-gradient(circle, #a855f7, transparent 65%); top: auto; left: auto; bottom: -40%; right: -20%; animation-delay: -8s; }
.uc-login-orbits { position: absolute; width: min(90vw, 900px); aspect-ratio: 1; top: 50%; left: 30%; transform: translate(-50%, -50%); }
.uc-login-ring { position: absolute; inset: 10%; border: 1px solid #67e8f926; border-radius: 50%; animation: ucRingSpin 30s linear infinite; box-shadow: 0 0 45px #22d3ee0d, inset 0 0 45px #6366f10d; }
.uc-login-ring:nth-child(2) { inset: 22%; border-color: #c084fc35; animation-duration: 22s; animation-direction: reverse; }
.uc-login-ring:nth-child(3) { inset: 34%; border-style: dashed; animation-duration: 45s; }
.uc-login-ring:before { content: ''; position: absolute; top: 50%; left: -4px; width: 7px; height: 7px; border-radius: 50%; background: #67e8f9; box-shadow: 0 0 20px 4px #22d3ee80; }
.uc-login-ring:nth-child(2):before { background: #e9d5ff; box-shadow: 0 0 20px 4px #a855f780; }
.uc-login-stars { position: absolute; inset: -10%; background-image: radial-gradient(circle, #a5f3fcaa 1px, transparent 2px), radial-gradient(circle, #c4b5fd88 1px, transparent 2px); background-size: 137px 151px, 211px 193px; background-position: 0 0, 60px 80px; animation: ucStarsFloat 24s ease-in-out infinite alternate; opacity: .45; }
@keyframes ucRingSpin { to { transform: rotate(360deg); } }
@keyframes ucAuraDrift { to { transform: translate(12%, 15%) scale(1.15); } }
@keyframes ucStarsFloat { to { transform: translate3d(20px, -30px, 0); opacity: .7; } }
@media (max-height: 720px) { .uc-login { padding-top: 16px; padding-bottom: 16px; } .uc-login .h-16 { width: 48px; height: 48px; } .uc-login .text-3xl { font-size: 1.5rem; } .uc-login .mt-8 { margin-top: 16px; } }
@media (prefers-reduced-motion: reduce) { .uc-login-universe * { animation: none !important; } }

/* Clip decorative layers so they cannot add blank scrollable space. */
html:has(.uc-login), body:has(.uc-login) { margin: 0; overflow-x: clip; }
body:has(.uc-login) > .uc-auth-enter { min-height: 100dvh; }
.uc-login { min-height: 100dvh; width: 100%; box-sizing: border-box; overflow: clip; }
.uc-login > .max-w-6xl { min-width: 0; }
.uc-login > .max-w-6xl > div { min-width: 0; }
@media (min-width: 1024px) and (min-height: 540px) {
    .uc-login { padding-block: 12px; }
    .uc-login .mb-8 { margin-bottom: 12px; }
    .uc-login form.space-y-5 > :not([hidden]) ~ :not([hidden]) { margin-top: 10px; }
    .uc-login form .p-4 { padding: 12px; }
}
</style>


    <div class="uc-login min-h-screen relative overflow-hidden bg-slate-950 text-white flex items-center justify-center px-4 py-5">

        <div class="uc-login-universe" aria-hidden="true">
            <div class="uc-login-aura"></div><div class="uc-login-aura purple"></div>
            <div class="uc-login-stars"></div>
            <div class="uc-login-orbits"><div class="uc-login-ring"></div><div class="uc-login-ring"></div><div class="uc-login-ring"></div></div>
        </div>
        {{-- Background Effects --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,.35),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(99,102,241,.35),transparent_35%)]"></div>

        <div class="absolute inset-0 bg-[linear-gradient(rgba(14,165,233,.10)_1px,transparent_1px),linear-gradient(90deg,rgba(99,102,241,.10)_1px,transparent_1px)] bg-[size:70px_70px]"></div>

        <div class="absolute -top-28 -left-28 h-96 w-96 rounded-full bg-cyan-500/30 blur-3xl animate-pulse"></div>

        <div class="absolute -bottom-28 -right-28 h-96 w-96 rounded-full bg-indigo-500/30 blur-3xl animate-pulse"></div>


        <div class="relative z-10 w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

            {{-- Left Side Hero --}}
            <div class="hidden lg:block">

                <div class="h-16 w-16 rounded-3xl bg-gradient-to-br from-cyan-500 via-blue-500 to-indigo-500 flex items-center justify-center shadow-2xl shadow-cyan-500/30">

                    <i class="fas fa-right-to-bracket text-3xl"></i>

                </div>


                <h1 class="mt-6 text-6xl font-black leading-tight">

                    Welcome Back to

                    <span class="block bg-gradient-to-r from-cyan-300 via-blue-300 to-indigo-300 bg-clip-text text-transparent">

                        University Connect

                    </span>

                </h1>


                <p class="mt-5 text-slate-300 text-lg max-w-xl">

                    Access your campus dashboard,
                    mentorship network, job portal,
                    events and smart university ecosystem.

                </p>


                <div class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-5 max-w-xl">

                    <div class="flex items-start gap-4">

                        <div class="h-11 w-11 rounded-2xl bg-cyan-500/15 text-cyan-300 flex items-center justify-center shrink-0">

                            <i class="fas fa-id-card"></i>

                        </div>


                        <div>

                            <p class="font-black text-white">
                                Student & Alumni Login
                            </p>

                            <p class="mt-1 text-sm text-slate-300 leading-6">

                                Students and Alumni can sign in using either
                                their verified email address or Official ID.

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Login Card --}}
            <div class="rounded-[2rem] border border-white/10 bg-white/10 backdrop-blur-2xl shadow-2xl p-7 md:p-9">


                {{-- Card Header --}}
                <div class="text-center mb-8">

                    <div class="mx-auto h-16 w-16 rounded-3xl bg-gradient-to-br from-cyan-500 via-blue-500 to-indigo-500 flex items-center justify-center shadow-2xl shadow-cyan-500/30">

                        <i class="fas fa-lock text-2xl"></i>

                    </div>


                    <h2 class="mt-5 text-3xl font-black">
                        Secure Login
                    </h2>


                    <p class="mt-2 text-sm text-slate-300">

                        Enter your email and password

                    </p>

                </div>


                {{-- Status Message --}}
                <x-auth-session-status
                    class="mb-4 text-emerald-300"
                    :status="session('status')"
                />


                {{-- Login Form --}}
                <form
                    method="POST"
                    action="{{ route('login') }}"
                    class="space-y-5"
                >

                    @csrf


                    {{-- Email / Official ID --}}
                    <div>

                        <label
                            for="login"
                            class="text-sm font-bold text-slate-200"
                        >

                            Email

                        </label>


                        <input
                            id="login"
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-cyan-400 focus:ring-cyan-400"
                            placeholder="Enter Your Verified Email"
                        >


                        <x-input-error
                            :messages="$errors->get('login')"
                            class="mt-2 text-red-300"
                        />

                    </div>


                    {{-- Password --}}
                    <div x-data="{ showPassword: false }">

                        <label
                            for="password"
                            class="text-sm font-bold text-slate-200"
                        >

                            Password

                        </label>


                        <div class="relative mt-2">
                        <input
                            id="password"
                            type="password"
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full rounded-2xl border border-white/10 bg-white/10 pl-4 pr-14 py-3 text-white placeholder-slate-400 focus:border-cyan-400 focus:ring-cyan-400"
                            placeholder="Enter password"
                        >
                        <button type="button" @click="showPassword = !showPassword"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'"
                            :aria-pressed="showPassword" aria-label="Show password" aria-pressed="false" aria-controls="password"
                            class="absolute inset-y-0 right-0 flex w-12 items-center justify-center rounded-r-2xl text-slate-300 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-cyan-400">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                        </button>
                        </div>


                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2 text-red-300"
                        />

                    </div>


                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between text-sm">

                        <label class="inline-flex items-center gap-2 text-slate-300">

                            <input
                                type="checkbox"
                                name="remember"
                                class="rounded border-white/20 bg-white/10 text-cyan-500 focus:ring-cyan-400"
                            >

                            Remember me

                        </label>


                        @if(Route::has('password.request'))

                            <a
                                href="{{ route('password.request') }}"
                                class="text-cyan-300 font-bold hover:text-white"
                            >

                                Forgot Password?

                            </a>

                        @endif

                    </div>


                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-500 py-3.5 font-black shadow-2xl shadow-cyan-500/30 hover:scale-[1.02] transition"
                    >

                        <i class="fas fa-fingerprint mr-2"></i>

                        Login Securely

                    </button>


                    {{-- Information --}}
                    <div class="rounded-2xl border border-cyan-400/15 bg-cyan-400/5 p-4">

                        <div class="flex items-start gap-3">

                            <i class="fas fa-circle-info text-cyan-300 mt-1"></i>

                            <p class="text-xs leading-5 text-slate-300">

                                Student and Alumni accounts are created from the
                                university verified database. No manual registration
                                is required.

                            </p>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-guest-layout>
