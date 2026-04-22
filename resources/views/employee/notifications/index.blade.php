<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="brand-kicker">Employee Hub</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">Notifications</h2>
            </div>
            <div class="flex items-center gap-3">
                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">Unread: {{ $unreadCount }}</span>
                <a href="{{ route('employee.home') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Back to Home</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('success') }}
                </div>
            @endif

            @if ($unreadCount > 0)
                <form method="POST" action="{{ route('employee.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Mark All as Read</button>
                </form>
            @endif

            <div class="space-y-4">
                @forelse ($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    <article class="brand-panel p-6 {{ $isUnread ? 'border-l-4 border-l-amber-400' : '' }}">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $data['title'] ?? 'Notification' }}</h3>
                                <p class="mt-1 text-xs uppercase tracking-wide text-slate-500">{{ strtoupper($data['category'] ?? 'general') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-500">{{ $notification->created_at->format('d M Y H:i') }}</p>
                                <span class="mt-1 inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $isUnread ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $isUnread ? 'Unread' : 'Read' }}
                                </span>
                            </div>
                        </div>

                        <p class="mt-3 text-sm text-slate-700">{{ $data['message'] ?? '' }}</p>

                        @if ($isUnread)
                            <form method="POST" action="{{ route('employee.notifications.read', $notification->id) }}" class="mt-4">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100">Mark as Read</button>
                            </form>
                        @endif
                    </article>
                @empty
                    <div class="brand-panel border-dashed p-8 text-center">
                        <p class="text-sm text-slate-600">No notifications yet.</p>
                    </div>
                @endforelse
            </div>

            <div>
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
