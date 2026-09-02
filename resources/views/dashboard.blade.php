@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <input type="text" placeholder="Search your apps..." class="panorly-input w-full max-w-md" id="panorly-search">
        <button class="panorly-btn ml-4" onclick="document.getElementById('add-app-modal').classList.remove('hidden')">+ Add app</button>
    </div>

    <div class="panorly-grid" data-panorly-sortable>
        @forelse($apps as $app)
            <a href="{{ $app->url }}" target="_blank" rel="noopener"
               data-app-id="{{ $app->id }}"
               class="panorly-card p-5 flex flex-col items-center gap-3 text-center panorly-app-item"
               data-search="{{ strtolower($app->title) }}">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg font-bold"
                     style="background-color: {{ $app->color ?? 'var(--panorly-accent)' }}">
                    {{ strtoupper(substr($app->title, 0, 1)) }}
                </div>
                <span class="text-sm font-medium">{{ $app->title }}</span>
            </a>
        @empty
            <p class="opacity-60 col-span-full text-center py-12">No apps yet. Add your first one.</p>
        @endforelse
    </div>

    <div id="add-app-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="panorly-card w-full max-w-sm p-6">
            <h2 class="text-lg font-semibold mb-4">Add a new app</h2>
            <form method="POST" action="{{ route('apps.store') }}" class="space-y-3">
                @csrf
                <input type="text" name="title" placeholder="Name" required class="panorly-input w-full">
                <input type="url" name="url" placeholder="https://" required class="panorly-input w-full">
                <input type="color" name="color" class="w-full h-10 rounded">
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="panorly-btn flex-1">Add</button>
                    <button type="button" class="flex-1 opacity-70" onclick="document.getElementById('add-app-modal').classList.add('hidden')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('panorly-search')?.addEventListener('input', (e) => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.panorly-app-item').forEach((el) => {
        el.style.display = el.dataset.search.includes(q) ? '' : 'none';
    });
});
</script>
@endsection
