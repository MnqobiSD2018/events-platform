<x-app-layout>
	<x-slot name="header">
		<div>
			<p class="brand-kicker">{{ isset($event) ? 'Update' : 'Create' }}</p>
			<h2 class="mt-2 text-2xl font-semibold leading-tight text-slate-900">
				{{ isset($event) ? 'Edit Event' : 'Create Event' }}
			</h2>
		</div>
	</x-slot>

	<div class="py-10">
		<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
			<div class="brand-panel p-6">
				@if ($errors->any())
					<div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
						<p class="mb-2 font-semibold">Please fix the following:</p>
						<ul class="list-disc space-y-1 pl-5">
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				<form method="POST" action="{{ isset($event) ? route('events.update', $event) : route('events.store') }}" class="space-y-5">
					@csrf
					@isset($event)
						@method('PUT')
					@endisset

					<div>
						<label for="name" class="block text-sm font-medium text-slate-700">Event Name</label>
						<input id="name" name="name" type="text" value="{{ old('name', $event->name ?? '') }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
					</div>

					<div>
						<label for="type" class="block text-sm font-medium text-slate-700">Event Type</label>
						<select id="type" name="type" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
							<option value="">Select a type</option>
							@foreach (['marathon', 'wellness program', 'training', 'workshop', 'team building', 'other'] as $eventType)
								<option value="{{ $eventType }}" @selected(old('type', $event->type ?? '') === $eventType)>
									{{ ucfirst($eventType) }}
								</option>
							@endforeach
						</select>
					</div>

					<div>
						<label for="event_date" class="block text-sm font-medium text-slate-700">Event Date</label>
						<input id="event_date" name="event_date" type="datetime-local" value="{{ old('event_date', isset($event) ? \Illuminate\Support\Carbon::parse($event->event_date)->format('Y-m-d\\TH:i') : '') }}" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
					</div>

					<div>
						<label for="description" class="block text-sm font-medium text-slate-700">Description</label>
						<textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('description', $event->description ?? '') }}</textarea>
					</div>

					<div class="flex items-center gap-3">
						<button type="submit" class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-medium text-white transition hover:bg-slate-700">
							{{ isset($event) ? 'Update Event' : 'Create Event' }}
						</button>
						<a href="{{ route('events.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</x-app-layout>
