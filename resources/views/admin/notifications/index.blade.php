<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="brand-kicker">Admin Panel</p>
                <h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">HR Notifications</h2>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900">
                    <ul class="list-disc space-y-1 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="brand-panel p-6 lg:col-span-1">
                    <h3 class="text-lg font-semibold text-slate-900">Send Notification</h3>
                    <p class="mt-1 text-sm text-slate-600">Broadcast an HR message to all employees.</p>

                    <form action="{{ route('admin.notifications.store') }}" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700">Title</label>
                            <input id="title" name="title" type="text" value="{{ old('title') }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-slate-700">Category</label>
                            <select id="category" name="category" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                                @foreach (['hr', 'wellness', 'policy', 'general'] as $category)
                                    <option value="{{ $category }}" @selected(old('category') === $category)>{{ strtoupper($category) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-slate-700">Message</label>
                            <textarea id="message" name="message" rows="6" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">Push to All Employees</button>
                    </form>
                </div>

                <div class="brand-panel p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-slate-900">Sent Notifications</h3>
                    @if ($broadcasts->count())
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Title</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Category</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Recipients</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Sent By</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Sent At</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($broadcasts as $broadcast)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-slate-800">
                                                <p class="font-medium">{{ $broadcast->title }}</p>
                                                <p class="text-slate-500">{{ \Illuminate\Support\Str::limit($broadcast->message, 90) }}</p>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ strtoupper($broadcast->category) }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ $broadcast->recipient_count }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ $broadcast->sender?->name ?: 'System' }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ $broadcast->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5">
                            {{ $broadcasts->links() }}
                        </div>
                    @else
                        <p class="mt-3 text-sm text-slate-600">No notifications sent yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
