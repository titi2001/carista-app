<?php

namespace App\Services;

use App\Models\ApiRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsApiService {

    protected string $apiKey;
    protected string $searchEndpoint;

    public function __construct(){
        $this->apiKey   = config('services.newsapi.key');
        $this->searchEndpoint = config('services.newsapi.search_endpoint');
    }

    public function search(string $query, string $date){
        $_return = [
            'success' => false,
            'data' => []
        ];
        $response = Http::get($this->searchEndpoint, [
            'q'        => $query,
            'apiKey'   => $this->apiKey,
            'from'     => $date,
        ]);
        if($response->successful()){
            $_return['success'] = $response->successful();
            $_return['data'] = $response->json();
            $latest_articles = collect($response->json()['articles'])->sortByDesc('publishedAt')->take(20);
            ApiRequest::create([
                'response_body' => json_encode($latest_articles)
            ]);
        }
        if(!$response->successful()){
            Log::error('News API failed', [
                'query' => $query,
                'date' => $date,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
        return $_return;
    }
}