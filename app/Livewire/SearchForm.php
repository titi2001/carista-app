<?php

namespace App\Livewire;

use App\Services\NewsApiService;
use Carbon\Carbon;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SearchForm extends Component
{
    use WithRateLimiting;

    #[Validate('required|max:100')] 
    public string $query = '';

    #[Validate('required|date|date_format:Y-m-d|before_or_equal:today')] 
    public $date;

    public array $articles = [];
    public int $perPage = 20;
    public int $page = 1;

    public array $chartData = [
        'labels' => [],
        'counts' => []
    ];

    public function search(NewsApiService $newsService)
    {
        $this->validate();

        try {
            $this->rateLimit(10);
        } catch (TooManyRequestsException $exception) {
            $this->addError('throttle', "Slow down! Please wait another {$exception->secondsUntilAvailable} seconds to search.");
            throw ValidationException::withMessages([
                'throttle' => "Slow down! Please wait another {$exception->secondsUntilAvailable} seconds to search.",
            ]);
        }

        $response = $newsService->search($this->query, $this->date);

        if ($response['success'] && isset($response['data']['articles'])) {
            $this->articles = collect($response['data']['articles'])
                ->map(function ($article) {
                    if (isset($article['publishedAt'])) {
                        $article['publishedAt'] = Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s');
                    }
                    return $article;
                })
                ->toArray();

            $this->page = 1;

            $this->calculateChartData();
            $this->dispatch('chartDataUpdated', $this->chartData);

        } else {
            $this->articles = [];
            $this->addError('search', "There has been an issue sending the request. Try again.");
        }
    }

    public function getPaginatedArticlesProperty(): LengthAwarePaginator
    {
        return $this->paginateCollection(collect($this->articles), $this->perPage, $this->page);
    }

    protected function paginateCollection($collection, int $perPage, int $page): LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $sliced = $collection->forPage($page, $perPage);

        return new LengthAwarePaginator(
            $sliced,
            $collection->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
    }

    public function gotoPage(int $page)
    {
        $this->page = $page;
    }

    public function calculateChartData()
    {
        $dates = collect(range(0, 6))
            ->map(fn($i) => Carbon::today()->subDays($i)->format('Y-m-d'))
            ->reverse();

        $counts = $dates->map(function ($date) {
            return collect($this->articles)
                ->filter(fn($article) => isset($article['publishedAt']) && substr($article['publishedAt'], 0, 10) === $date)
                ->count();
        });

        $this->chartData = [
            'labels' => array_values($dates->toArray()),
            'counts' => array_values($counts->toArray()),
        ];
    }

    public function render()
    {
        return view('livewire.search-form');
    }
}
