<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="brand-kicker">Updates</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">Company & HR Announcements</h2>
            </div>
            <a href="{{ route('employee.home') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Back to Home</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('success') }}
                </div>
            @endif

            <div class="brand-panel p-4">
                <form method="GET" action="{{ route('employee.announcements.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div>
                        <label for="category" class="block text-sm font-medium text-slate-700">Filter by Category</label>
                        <select id="category" name="category" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:w-56">
                            <option value="">All Categories</option>
                            @foreach ($allowedCategories as $category)
                                <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Apply Filter</button>
                </form>
            </div>

            <div class="space-y-4">
                @forelse ($announcements as $announcement)
                    @php $isRead = in_array($announcement->id, $readAnnouncementIds, true); @endphp
                    <article class="brand-panel p-6">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $announcement->title }}</h3>
                                <p class="mt-1 text-xs uppercase tracking-wide text-slate-500">
                                    {{ ucfirst($announcement->category) }}
                                    @if ($announcement->published_at)
                                        • {{ $announcement->published_at->format('d M Y H:i') }}
                                    @endif
                                </p>
                            </div>
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $isRead ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $isRead ? 'Read' : 'Unread' }}
                            </span>
                        </div>

                        <p class="mt-3 text-sm text-slate-700">{{ $announcement->body }}</p>

                        @unless ($isRead)
                            <form method="POST" action="{{ route('employee.announcements.read', $announcement) }}" class="mt-4">
                                @csrf
                                <button type="submit" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                    Mark as Read
                                </button>
                            </form>
                        @endunless
                    </article>
                @empty
                    <div class="brand-panel border-dashed p-8 text-center">
                        <p class="text-sm text-slate-600">No announcements found for this category yet.</p>
                    </div>
                @endforelse
            </div>

            <div>
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
