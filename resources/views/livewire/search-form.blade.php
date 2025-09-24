<div class="w-full flex flex-col gap-4" wire:loading.class="opacity-50 pointer-events-none" wire:target="search">
    <form wire:submit.prevent="search" class="flex flex-col gap-2 mb-4">
        <div class="flex flex-row gap-4 items-start">
            <div class="flex flex-col">
                <input wire:model="query" type="text" placeholder="Search..." class="border p-1" />
                @error('query') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col">
                <input wire:model="date" type="date" max="{{ date('Y-m-d') }}" class="border p-1" />
                @error('date') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="cursor-pointer border p-1 self-start">Search</button>
            @error('throttle') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
            @error('search') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
    </form>

    <canvas id="articlesChart" class="w-full h-32 {{ $this->paginatedArticles->count() ? '' : 'hidden' }}"></canvas>
    @if($this->paginatedArticles->count()) 

        <ul class="space-y-2 mt-4">
            @foreach($this->paginatedArticles as $article)
                <li class="border p-2 rounded flex flex-col gap-2">
                    <h2 class="font-bold">{{ $article['title'] }}</h2>
                    <p class="font-bold">{{ $article['source']['name'] ?? '' }}</p>
                    <span>{{ $article['publishedAt'] ?? '' }}</span>
                    <a href="{{ $article['url'] }}" target="_blank">{{ $article['url'] }}</a>
                </li>
            @endforeach
        </ul>

        <div class="mt-4 flex gap-2">
            @foreach(range(1, $this->paginatedArticles->lastPage()) as $p)
                <button 
                    wire:click="gotoPage({{ $p }})"
                    class="p-1 rounded border cursor-pointer {{ $p == $this->paginatedArticles->currentPage() ? 'text-xl' : '' }}">
                    {{ $p }}
                </button>
            @endforeach
        </div>
    @else
        <p>No articles found.</p>
    @endif
</div>
