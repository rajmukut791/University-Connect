<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-bold">Edit {{ ucfirst($type) }} Post</h2></x-slot>
    <div class="mx-auto max-w-3xl rounded-2xl bg-slate-900 p-6 text-white">
        @if($errors->any())
            <div class="mb-5 text-red-300">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
        @endif
        @if(!in_array(auth()->user()->role, ['admin', 'super_admin'], true))
            <p class="mb-5 text-amber-300">Your updated post will be submitted for approval.</p>
        @endif
        @php
            $fields = ['title' => ['Title', 'text'], 'description' => ['Description', 'textarea']];
            $fields += match ($type) {
                'job' => ['company_name' => ['Company', 'text'], 'location' => ['Location', 'text'], 'type' => ['Job type', 'job_type'], 'experience_level' => ['Experience level', 'experience'], 'salary_range' => ['Salary', 'text'], 'requirements' => ['Requirements', 'textarea'], 'benefits' => ['Benefits', 'textarea'], 'positions_available' => ['Vacancies', 'number'], 'contact_email' => ['Contact email', 'email'], 'contact_phone' => ['Contact phone', 'text'], 'application_url' => ['Application website', 'url'], 'deadline' => ['Deadline', 'date']],
                'event' => ['type' => ['Event type', 'text'], 'location' => ['Location', 'text'], 'start_date' => ['Start date', 'date'], 'start_time' => ['Start time', 'time'], 'end_date' => ['End date', 'date'], 'end_time' => ['End time', 'time'], 'capacity' => ['Capacity', 'number']],
                'donation' => ['bkash_number' => ['bKash receiving number', 'tel'], 'nagad_number' => ['Nagad receiving number', 'tel'], 'category' => ['Category', 'text'], 'target_amount' => ['Target amount (BDT)', 'number'], 'deadline' => ['Deadline', 'date']],
            };
            $imageField = match ($type) { 'job' => 'job_image', 'event' => 'cover_image', 'donation' => 'image' };
        @endphp
        <form method="POST" action="{{ route('posts.update', ['type' => $type, 'id' => $post->id]) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PATCH')
            @foreach($fields as $name => [$label, $inputType])
                @php
                    $value = $post->{$name};
                    if($inputType === 'date' && $value) $value = \Illuminate\Support\Carbon::parse($value)->format('Y-m-d');
                    if($inputType === 'time' && $value) $value = substr($value, 0, 5);
                    $value = old($name, $value);
                @endphp
                <div>
                    <label for="{{ $name }}" class="mb-2 block font-bold">{{ $label }}</label>
                    @if($inputType === 'textarea')
                        <textarea id="{{ $name }}" name="{{ $name }}" rows="5" class="w-full rounded-xl border-slate-600 bg-slate-800">{{ $value }}</textarea>
                    @elseif(in_array($inputType, ['job_type', 'experience']))
                        <select id="{{ $name }}" name="{{ $name }}" class="w-full rounded-xl border-slate-600 bg-slate-800">
                            @foreach($inputType === 'job_type' ? ['full_time','part_time','internship','contract','temporary','remote','hybrid'] : ['entry','mid','senior'] as $option)
                                <option value="{{ $option }}" @selected($value === $option)>{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                            @endforeach
                        </select>
                    @else
                        <input id="{{ $name }}" name="{{ $name }}" type="{{ $inputType }}" value="{{ $value }}" @if($inputType === 'number') min="1" step="{{ $name === 'target_amount' ? '0.01' : '1' }}" @endif class="w-full rounded-xl border-slate-600 bg-slate-800">
                    @endif
                </div>
            @endforeach
            <div><label for="{{ $imageField }}" class="mb-2 block font-bold">Replace image (optional)</label><input id="{{ $imageField }}" type="file" name="{{ $imageField }}" accept="image/jpeg,image/png,image/webp"></div>
            <div class="flex gap-3"><button class="rounded-xl bg-indigo-600 px-5 py-3 font-bold" type="submit">Update Post</button><a href="{{ route('newsfeed.index') }}" class="rounded-xl border border-slate-600 px-5 py-3">Cancel</a></div>
        </form>
    </div>
</x-app-layout>
