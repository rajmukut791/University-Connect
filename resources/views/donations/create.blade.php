<x-app-layout>
    <div class="min-h-screen text-white">
        <style>
            @keyframes donateFloat {
                0%,100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-14px) scale(1.03); }
            }

            @keyframes donateShine {
                0% { transform: translateX(-130%); }
                100% { transform: translateX(130%); }
            }

            @keyframes donateGlow {
                0%,100% { box-shadow: 0 22px 70px rgba(20,184,166,.20); }
                50% { box-shadow: 0 28px 95px rgba(168,85,247,.28); }
            }

            .donate-orb {
                animation: donateFloat 7s ease-in-out infinite;
            }

            .donate-card {
                animation: donateGlow 5s ease-in-out infinite;
            }

            .donate-shine::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,.08), transparent);
                transform: translateX(-130%);
                animation: donateShine 7s ease-in-out infinite;
                pointer-events: none;
            }

            .donate-input {
                width: 100%;
                border-radius: 1.25rem;
                border: 1px solid rgba(255,255,255,.12);
                background: rgba(2,6,23,.75);
                padding: 1rem 1.1rem;
                color: white;
                font-size: .875rem;
                font-weight: 800;
                outline: none;
                transition: .25s ease;
            }

            .donate-input::placeholder {
                color: rgba(148,163,184,.75);
            }

            .donate-input:focus {
                border-color: rgba(45,212,191,.80);
                box-shadow: 0 0 0 5px rgba(45,212,191,.12);
                transform: translateY(-2px);
            }

            .donate-label {
                display: flex;
                align-items: center;
                gap: .55rem;
                margin-bottom: .65rem;
                color: rgb(226 232 240);
                font-size: .85rem;
                font-weight: 950;
            }
        </style>

        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-950 via-emerald-950 to-indigo-950 p-6 shadow-2xl md:p-8">

            <div class="donate-orb absolute -top-24 -right-24 h-80 w-80 rounded-full bg-emerald-500/20 blur-3xl"></div>
            <div class="donate-orb absolute top-56 -left-28 h-80 w-80 rounded-full bg-cyan-500/20 blur-3xl" style="animation-delay:2s"></div>
            <div class="donate-orb absolute -bottom-28 right-1/3 h-80 w-80 rounded-full bg-fuchsia-500/15 blur-3xl" style="animation-delay:4s"></div>

            <div class="uc-compact-header relative z-10 mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-4 py-2 text-xs font-black uppercase tracking-[.3em] text-emerald-300">
                        <i class="fas fa-hand-holding-heart"></i>
                        Donation Campaign
                    </div>

                    <h1 class="text-4xl font-black leading-tight md:text-5xl">
                        Create Donation Campaign
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm font-semibold text-slate-400">
                        Create a professional fundraising campaign for student support, event funding, scholarship, or emergency help.
                    </p>
                </div>

                <a href="{{ route('donations.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-black text-white shadow-xl transition hover:scale-105 hover:bg-white/15">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
            </div>

            @if ($errors->any())
                <div class="relative z-10 mb-6 rounded-2xl border border-red-500/30 bg-red-500/10 p-5 text-sm font-bold text-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('donations.store') }}"
                  enctype="multipart/form-data"
                  class="donate-card donate-shine relative z-10 mx-auto max-w-5xl overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.06] p-5 shadow-2xl backdrop-blur-2xl md:p-7">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach(['bkash_number'=>'bKash receiving number','nagad_number'=>'Nagad receiving number'] as $field=>$label)
                        <label class="block font-bold">{{ $label }}<input type="tel" name="{{ $field }}" value="{{ old($field) }}" placeholder="Your authorized receiving number" class="mt-2 block w-full rounded-xl border border-white/10 bg-slate-950 p-3 text-white">@error($field)<span class="text-red-400">{{ $message }}</span>@enderror</label>
                    @endforeach
                </div>
                <p class="text-sm text-slate-400">Leave a method blank to disable it. Only enter an account you are authorized to collect donations into.</p>


                <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

                    <div class="xl:col-span-12">
                        <label class="donate-label">
                            <i class="fas fa-heading text-cyan-300"></i>
                            Campaign Title
                        </label>
                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               required
                               placeholder="Example: Help CSE Students Build Innovation Lab"
                               class="donate-input">
                    </div>

                    <div class="xl:col-span-4">
                        <label class="donate-label">
                            <i class="fas fa-layer-group text-purple-300"></i>
                            Category
                        </label>
                        <select name="category" required class="donate-input">
                            <option value="Student Support" {{ old('category') === 'Student Support' ? 'selected' : '' }}>Student Support</option>
                            <option value="Scholarship" {{ old('category') === 'Scholarship' ? 'selected' : '' }}>Scholarship</option>
                            <option value="Emergency Fund" {{ old('category') === 'Emergency Fund' ? 'selected' : '' }}>Emergency Fund</option>
                            <option value="Event Fund" {{ old('category') === 'Event Fund' ? 'selected' : '' }}>Event Fund</option>
                            <option value="Department Support" {{ old('category') === 'Department Support' ? 'selected' : '' }}>Department Support</option>
                        </select>
                    </div>

                    <div class="xl:col-span-4">
                        <label class="donate-label">
                            <i class="fas fa-bangladeshi-taka-sign text-emerald-300"></i>
                            Target Amount
                        </label>
                        <input type="number"
                               name="target_amount"
                               value="{{ old('target_amount') }}"
                               min="1"
                               required
                               placeholder="Example: 50000"
                               class="donate-input">
                    </div>

                    <div class="xl:col-span-4">
                        <label class="donate-label">
                            <i class="fas fa-calendar-check text-pink-300"></i>
                            Deadline
                        </label>
                        <input type="date"
                               name="deadline"
                               value="{{ old('deadline') }}"
                               required
                               class="donate-input">
                    </div>

                    <div class="xl:col-span-12">
                        <label class="donate-label">
                            <i class="fas fa-image text-yellow-300"></i>
                            Campaign Image
                        </label>

                        <label class="group flex cursor-pointer flex-col items-center justify-center rounded-[1.6rem] border border-dashed border-white/20 bg-slate-950/60 px-6 py-8 text-center transition hover:border-emerald-400/60 hover:bg-emerald-400/5">
                            <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 via-cyan-500 to-indigo-600 text-2xl shadow-xl transition group-hover:scale-110">
                                <i class="fas fa-cloud-arrow-up"></i>
                            </div>

                            <p class="text-base font-black text-white">
                                Upload Campaign Cover Image
                            </p>

                            <p class="mt-1 text-xs font-semibold text-slate-400">
                                JPG, PNG, WEBP allowed. Recommended size 1200×700.
                            </p>

                            <input type="file"
                                   name="image"
                                   accept="image/*"
                                   class="hidden">
                        </label>
                    </div>

                    <div class="xl:col-span-12">
                        <label class="donate-label">
                            <i class="fas fa-align-left text-emerald-300"></i>
                            Description
                        </label>
                        <textarea name="description"
                                  rows="7"
                                  required
                                  placeholder="Write campaign purpose, who will benefit, why support is needed, and how the fund will be used..."
                                  class="donate-input min-h-[190px]">{{ old('description') }}</textarea>
                    </div>

                    <div class="xl:col-span-12">
                        <div class="rounded-[1.6rem] border border-emerald-400/20 bg-emerald-500/10 p-5">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-400/15 text-emerald-300">
                                    <i class="fas fa-shield-halved"></i>
                                </div>

                                <div>
                                    <h3 class="text-lg font-black text-emerald-300">
                                        Campaign Review Flow
                                    </h3>
                                    <p class="mt-1 text-sm font-semibold text-slate-400">
                                        Campaign information should be clear and trustworthy. Admin can review, approve, reject, or manage donation campaigns.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                    <a href="{{ route('donations.index') }}"
                       class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-6 py-3 text-sm font-black text-white transition hover:bg-white/15">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 via-cyan-500 to-indigo-600 px-8 py-3 text-sm font-black text-white shadow-xl shadow-emerald-500/25 transition hover:scale-105">
                        <i class="fas fa-paper-plane"></i>
                        Submit Campaign
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>