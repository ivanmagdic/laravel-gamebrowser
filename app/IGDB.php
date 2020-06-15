<?php


namespace App;


use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class IGDB
{
    private string $apiKey;
    protected string $endpoint = 'https://api-v3.igdb.com/games';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function mostAnticipated($months = 6): Collection
    {
        $now = Carbon::now()->timestamp;
        $until = Carbon::now()->addMonths($months)->timestamp;

        $body = "
            fields name, cover.url, first_release_date, popularity, platforms.abbreviation, rating;
            where platforms = (48,49,6)
            & (first_release_date >= {$now}
            & first_release_date < {$until});
            sort popularity desc;
            limit 16;
        ";

        return $this->formatForList($this->fetch($body));
    }

    public function popularForPC(): Collection
    {
        $from = Carbon::now()->subMonth(3)->timestamp;
        $to = Carbon::now()->addMonths(3)->timestamp;

        $body = "
            fields name, cover.url, first_release_date, popularity, platforms.abbreviation, rating;
            where platforms = (6)
            & (first_release_date >= {$from}
            & first_release_date < {$to});
            sort popularity desc;
            limit 16;
        ";

        return $this->formatForList($this->fetch($body));
    }

    public function popularForPS4(): Collection
    {
        $from = Carbon::now()->subMonth(3)->timestamp;
        $to = Carbon::now()->addMonths(3)->timestamp;

        $body = "
            fields name, cover.url, first_release_date, popularity, platforms.abbreviation, rating;
            where platforms = (48)
            & (first_release_date >= {$from}
            & first_release_date < {$to});
            sort popularity desc;
            limit 16;
        ";

        return $this->formatForList($this->fetch($body));
    }

    public function popularForXONE(): Collection
    {
        $from = Carbon::now()->subMonth(3)->timestamp;
        $to = Carbon::now()->addMonths(3)->timestamp;

        $body = "
            fields name, cover.url, first_release_date, popularity, platforms.abbreviation, rating;
            where platforms = (49)
            & (first_release_date >= {$from}
            & first_release_date < {$to});
            sort popularity desc;
            limit 16;
        ";

        return $this->formatForList($this->fetch($body));
    }

    public function search(string $keyword): Collection
    {
        $body = "
            fields name, cover.url, first_release_date, popularity, platforms.abbreviation, rating;
            search \"{$keyword}\";
            limit 40;
        ";

        return $this->formatForList($this->fetch($body));
    }

    public function game(string $id)
    {
        $body = "
            fields name, summary, storyline, genres.name, cover.url, first_release_date, popularity, platforms.abbreviation, rating;
            where id = {$id};
        ";

        return $this->formatForDetails($this->fetch($body));
    }

    protected function fetch(string $body): array
    {
        return Http::withHeaders([
            'user-key' => $this->apiKey,
        ])
            ->withOptions([
                'body' => $body
            ])->get($this->endpoint)
            ->json();
    }

    protected function formatForList($games): Collection
    {
        return collect($games)->map(function ($game) {
            return [
                'id' => $game['id'],
                'name' => $game['name'],
                'image' => isset($game['cover']['url']) ? str_replace('t_thumb', 't_cover_big',
                    $game['cover']['url']) : 'https://via.placeholder.com/150',
                'rating' => isset($game['rating']) ? $game['rating'] / 20 : null,
                'first_release_date' => isset($game['first_release_date']) ? Carbon::createFromTimestamp($game['first_release_date'])->toDateString() : null,
                'platforms' => isset($game['platforms']) ? collect($game['platforms'])->pluck('abbreviation')->toArray() : null,
            ];
        });
    }

    protected function formatForDetails($games)
    {
        return collect($games)->map(function ($game) {
            return [
                'id' => $game['id'],
                'name' => $game['name'],
                'storyline' => isset($game['storyline']) ? $game['storyline'] : null,
                'summary' => isset($game['summary']) ? $game['summary'] : null,
                'image' => isset($game['cover']['url']) ? str_replace('t_thumb', 't_cover_big',
                    $game['cover']['url']) : 'https://via.placeholder.com/150',
                'rating' => isset($game['rating']) ? $game['rating'] / 20 : null,
                'first_release_date' => isset($game['first_release_date']) ? Carbon::createFromTimestamp($game['first_release_date'])->toDateString() : null,
                'platforms' => isset($game['platforms']) ? collect($game['platforms'])->pluck('abbreviation')->toArray() : null,
                'genres' => isset($game['genres']) ? collect($game['genres'])->pluck('name')->toArray() : null,
            ];
        })->first();
    }
}
