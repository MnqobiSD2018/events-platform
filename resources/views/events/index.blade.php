<x-app-layout>
	<x-slot name="header">
		<div class="flex items-center justify-between gap-4">
			<div>
				<p class="brand-kicker">Events</p>
				<h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">Your Event Portfolio</h2>
			</div>
			<a href="{{ route('events.create') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">
				Create Event
			</a>
		</div>
	</x-slot>

	<div class="py-10">
		<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
			@if (session('success'))
				<div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
					{{ session('success') }}
				</div>
			@endif

			@if ($events->count())
				<div class="brand-panel overflow-hidden">
					<table class="min-w-full divide-y divide-slate-200">
						<thead class="bg-slate-50">
							<tr>
								<th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Name</th>
								<th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Type</th>
								<th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Date</th>
								<th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-slate-100">
							@foreach ($events as $event)
								<tr>
									<td class="px-6 py-4">
										<p class="font-medium text-slate-900">{{ $event->name }}</p>
										<p class="text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($event->description, 60) }}</p>
									</td>
									<td class="px-6 py-4 text-sm text-slate-700">{{ ucfirst($event->type) }}</td>
									<td class="px-6 py-4 text-sm text-slate-700">{{ \Illuminate\Support\Carbon::parse($event->event_date)->format('d M Y H:i') }}</td>
									<td class="px-6 py-4 text-right">
										<div class="inline-flex items-center gap-3 text-sm">
											<a href="{{ route('events.show', $event) }}" class="font-medium text-blue-700 hover:text-blue-500">View</a>
											<a href="{{ route('events.edit', $event) }}" class="font-medium text-amber-700 hover:text-amber-500">Edit</a>
											<form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Delete this event?');">
												@csrf
												@method('DELETE')
												<button type="submit" class="font-medium text-rose-700 hover:text-rose-500">Delete</button>
											</form>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>

				<div class="mt-6">
					{{ $events->links() }}
				</div>
			@else
				<div class="brand-panel border-dashed p-10 text-center">
					<h3 class="text-lg font-semibold text-slate-900">No events yet</h3>
					<p class="mt-2 text-sm text-slate-600">Create your first event to start uploading participants and tracking attendance.</p>
					<a href="{{ route('events.create') }}" class="mt-5 inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">
						Create Your First Event
					</a>
				</div>
			@endif
		</div>
	</div>
</x-app-layout>
