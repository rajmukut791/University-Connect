<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-black text-white">Profile Settings</h2>
            <a href="{{ route('dashboard') }}" class="border border-white/10 bg-white/5 text-white">Back to Dashboard</a>
        </div>
    </x-slot>

    @php
        $sections = app(\App\Services\ProfileSettingsService::class)->sections($user);
        $identityId = $user->official_id ?: ($user->isAlumni() ? $user->alumni_id : $user->student_id);
    @endphp

    <style>
        .uc-settings { display: grid; grid-template-columns: 240px minmax(0,1fr); gap: 16px; align-items: start; }
        .uc-settings-identity,.uc-settings-card { border: 1px solid #ffffff18; border-radius: 18px; background: linear-gradient(135deg,#0f172aee,#252345b8); padding: 16px; }
        .uc-settings-identity { text-align: center; }
        .uc-settings-identity img { max-height: 120px !important; max-width: 120px !important; }
        .uc-settings-identity > div { width: 80px; height: 80px; font-size: 28px; }
        .uc-settings-grid { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 12px; }
        .uc-settings-card { min-width: 0; }
        .uc-settings-card header { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 10px; }
        .uc-settings-card h3 { font-size: 13px; font-weight: 800; }
        .uc-settings-card h3 i { color: #a5b4fc; margin-right: 6px; }
        .uc-settings-card p { font-size: 12px; color: #cbd5e1; overflow-wrap: anywhere; margin-top: 3px; }
        .uc-settings-edit { color: #c4b5fd; background: #8b5cf61a; border: 1px solid #a78bfa33; border-radius: 9px; padding: 7px 12px; font-size: 12px; font-weight: 800; }
        .uc-settings-edit:focus-visible { outline: 2px solid #67e8f9; outline-offset: 3px; }
        .uc-settings-dialog { width: min(680px,calc(100% - 24px)); max-height: calc(100dvh - 32px); padding: 0; color: #f8fafc; background: #0f172a; border: 1px solid #a78bfa40; border-radius: 20px; box-shadow: 0 30px 90px #0008; }
        .uc-settings-dialog::backdrop { background: #020617b8; backdrop-filter: blur(7px); }
        .uc-settings-dialog > header { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 16px 20px; border-bottom: 1px solid #ffffff15; }
        .uc-settings-dialog form,.uc-settings-history { padding: 20px; }
        .uc-settings-fields { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 14px; }
        .uc-settings-fields label { display: block; font-size: 13px; font-weight: 700; }
        .uc-settings-fields :is(input:not([type=checkbox]),textarea) { display: block; margin-top: 6px; width: 100%; border: 1px solid #ffffff20; border-radius: 10px; background: #020617; color: white; padding: 10px 12px; font-size: 13px; }
        .uc-settings-wide { grid-column: 1 / -1; }
        .uc-settings-dialog footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px; }
        .uc-settings-history { display: grid; gap: 14px; }
        .uc-settings-history :is(h3,.text-2xl,.text-3xl) { font-size: 18px; }
        @media(min-width:1100px) { .uc-settings-grid { grid-template-columns: repeat(3,minmax(0,1fr)); } .uc-settings-card { padding: 14px; } }
        @media(max-width:800px) { .uc-settings { grid-template-columns: 1fr; } .uc-settings-identity img { max-height: 80px; } }
        @media(max-width:540px) { .uc-settings-grid,.uc-settings-fields { grid-template-columns: 1fr; } }
    </style>

    @if(session('status') || session('success'))
        <p role="status" class="mb-3 rounded-xl border border-emerald-400/20 bg-emerald-500/10 p-3 text-sm text-emerald-300">{{ session('success') ?: 'Profile updated successfully.' }}</p>
    @endif
    @if($errors->any())
        <div role="alert" class="mb-3 rounded-xl border border-red-400/20 bg-red-500/10 p-3 text-sm text-red-300">Please correct the highlighted fields. Your changes have not been saved.</div>
    @endif

    <div class="uc-settings">
        <aside class="uc-settings-identity">
            <x-profile-photo :user="$user" />
            <h3 class="mt-3 font-black text-white">{{ $user->name }}</h3>
            <p class="mt-1 text-xs text-cyan-300">{{ ucwords(str_replace('_',' ',$user->role)) }}</p>
            <p class="mt-3 break-all text-xs text-slate-300">{{ $user->email }}</p>
            @if($identityId)<p class="mt-1 text-xs text-slate-400">ID: {{ $identityId }}</p>@endif
            <p class="mt-3 text-xs text-emerald-300">Profile completion: {{ $profileScore }}%</p>
            @if($user->isStudent() || $user->isAlumni())
                <p class="mt-3 text-xs leading-5 text-slate-400">Name, email and university ID are managed by an administrator.</p>
            @endif
        </aside>
        <div class="uc-settings-grid">
            @foreach($sections as $key => $section)
                <section class="uc-settings-card">
                    <header>
                        <h3><i class="fas {{ $section['icon'] }}" aria-hidden="true"></i>{{ $section['title'] }}</h3>
                        <button type="button" class="uc-settings-edit" data-settings-open="view-{{ $key }}" aria-label="View {{ $section['title'] }}">View</button>
                        <button type="button" class="uc-settings-edit" data-settings-open="settings-{{ $key }}" aria-label="Edit {{ $section['title'] }}">Edit</button>
                    </header>
                    @foreach(collect($section['fields'])->reject(fn($field) => $field[1] === 'file')->take(2) as $field => $definition)
                        <p><span class="text-slate-400">{{ $definition[0] }}:</span>
                            {{ $definition[1] === 'checkbox' ? ($user->$field ? 'Request first' : 'Direct messaging allowed') : (filled($user->$field) ? \Illuminate\Support\Str::limit($user->$field,75) : 'Not provided') }}
                        </p>
                    @endforeach
                    @if(count($section['fields']) > 2)<p class="text-slate-400">{{ count($section['fields']) }} settings available</p>@endif
                </section>
            @endforeach
            @if($user->isAlumni())
                <section class="uc-settings-card">
                    <header><h3><i class="fas fa-building" aria-hidden="true"></i>Work Experience</h3><button type="button" class="uc-settings-edit" data-settings-open="settings-history">Manage</button></header>
                    <p>{{ $workExperiences->count() }} experience records</p>
                    <p>{{ $user->work_experience_years ?: 'Experience not provided' }}</p>
                </section>
            @endif
        </div>
    </div>

    @if($user->isStudent())
        <section class="uc-settings-card mt-3"><header><h3>Account Status / Alumni Application</h3><a class="uc-settings-edit" href="{{ route('alumni-conversion.create') }}">Apply / View Status</a></header><p>Graduated students can apply for alumni status. Administrator reviews the application.</p></section>
    @endif
    @foreach($sections as $key => $section)
        <dialog id="view-{{ $key }}" class="uc-settings-dialog" aria-labelledby="view-title-{{ $key }}">
            <header><h3 id="view-title-{{ $key }}" class="font-black">{{ $section['title'] }}</h3><button type="button" class="uc-settings-edit" data-settings-close>Close</button></header>
            <dl class="p-5 space-y-4">
                @foreach($section['fields'] as $field => [$label,$type])
                    @if($type !== 'file')<div><dt class="text-xs text-slate-400">{{ $label }}</dt><dd class="mt-1 whitespace-pre-line break-words text-sm">{{ $type === 'checkbox' ? ($user->$field ? 'Request Required' : 'No Request Needed') : (filled($user->$field) ? $user->$field : 'Not provided') }}</dd></div>@endif
                @endforeach
            </dl>
        </dialog>
        <dialog id="settings-{{ $key }}" class="uc-settings-dialog" aria-labelledby="settings-title-{{ $key }}">
            <header><h3 id="settings-title-{{ $key }}" class="font-black">Edit {{ $section['title'] }}</h3><button type="button" data-settings-close aria-label="Close editor" class="uc-settings-edit">Close</button></header>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PATCH')
                <input type="hidden" name="_profile_section" value="{{ $key }}">
                <div class="uc-settings-fields">
                    @foreach($section['fields'] as $field => [$label,$type])
                        <div @class(['uc-settings-wide' => in_array($type,['textarea','checkbox','file'])])>
                            @if($type === 'checkbox')
                                <input type="hidden" name="{{ $field }}" value="0">
                                <label><input type="checkbox" name="{{ $field }}" value="1" @checked(old('_profile_section') === $key ? old($field) : $user->$field)> {{ $label }}</label>
                            @else
                                <label for="settings-{{ $field }}">{{ $label }}</label>
                                @if($type === 'textarea')
                                    <textarea id="settings-{{ $field }}" name="{{ $field }}" rows="4">{{ old('_profile_section') === $key ? old($field,$user->$field) : $user->$field }}</textarea>
                                @elseif($type === 'file')
                                    <input id="settings-{{ $field }}" type="file" name="{{ $field }}" accept=".jpg,.jpeg,.png,.webp">
                                    <p class="mt-1 text-xs text-slate-400">JPG, JPEG, PNG or WEBP, maximum 2MB.</p>
                                @else
                                    <input id="settings-{{ $field }}" type="{{ $type }}" name="{{ $field }}" value="{{ old('_profile_section') === $key ? old($field,$user->$field) : $user->$field }}" @required($field === 'name')>
                                @endif
                            @endif
                            @error($field)<p class="mt-1 text-xs text-red-300">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                </div>
                <footer><button type="button" class="uc-settings-edit" data-settings-close>Cancel</button><button type="submit" class="rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-2 text-sm font-bold text-white">Save Changes</button></footer>
            </form>
        </dialog>
    @endforeach
    @if($user->isAlumni())
        <dialog id="settings-history" class="uc-settings-dialog" aria-labelledby="settings-history-title">
            <header><h3 id="settings-history-title" class="font-black">Work Experience</h3><button type="button" class="uc-settings-edit" data-settings-close>Close</button></header>
            <div class="uc-settings-history">@include('profile.partials.settings-work-history')</div>
        </dialog>
    @endif
    <script>
        (() => {
            document.querySelectorAll('[data-settings-open]').forEach(button => button.addEventListener('click', () => {
                const dialog = document.getElementById(button.dataset.settingsOpen);
                dialog.querySelectorAll('form').forEach(form => form.reset());
                dialog.showModal();
            }));
            document.querySelectorAll('[data-settings-close]').forEach(button => button.addEventListener('click', () => button.closest('dialog').close()));
            const failedSection = @json($errors->any() ? old('_profile_section','history') : null);
            if (failedSection) document.getElementById('settings-' + failedSection)?.showModal();
            @if($user->isAlumni())
                if (location.hash === '#alumni-experience') document.getElementById('settings-history').showModal();
            @endif
        })();
    </script>
</x-app-layout>
