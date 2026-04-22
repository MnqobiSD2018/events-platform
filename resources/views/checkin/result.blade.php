<x-guest-layout>
    <div>
        <p class="brand-kicker">Check-in</p>
        <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">Check-in Result</h2>
    </div>

    <div class="mt-6">
        <div class="brand-panel border {{ $success ? 'border-emerald-200 bg-emerald-50/90' : 'border-amber-200 bg-amber-50/90' }} p-6">
            <h3 class="text-lg font-semibold {{ $success ? 'text-emerald-900' : 'text-amber-900' }}">{{ $message }}</h3>
            <p class="mt-2 text-sm {{ $success ? 'text-emerald-800' : 'text-amber-800' }}">
                Participant: <span class="font-medium">{{ $participant->name }}</span> ({{ $participant->email }})
            </p>
            <p class="mt-1 text-sm {{ $success ? 'text-emerald-800' : 'text-amber-800' }}">
                Event ID: {{ $participant->event_id }}
            </p>
        </div>

        <div class="mt-5 flex items-center gap-3">
            <a href="{{ url('/') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Back to Home</a>
            <a href="{{ route('login') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Log In</a>
        </div>
    </div>
</x-guest-layout>
