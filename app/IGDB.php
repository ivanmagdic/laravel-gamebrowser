<?php


namespace App;


use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IGDB
{
    private string $clientId;
    private string $clientSecret;
    protected string $endpoint = 'https://api.igdb.com/v4/games';

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function mostAnticipated($months = 6): Collection
    {
        $now = Carbon::now()->timestamp;
        $until = Carbon::now()->addMonths($months)->timestamp;

        $body = "
            fields name, cover.url, first_release_date, platforms.abbreviation, rating, follows;
            where platforms = (48,49,6)
            & (first_release_date >= {$now}
            & first_release_date < {$until})
            & follows != null;
            sort follows desc;
            limit 16;
        ";

        return $this->formatForList($this->fetch($body));
    }

    public function popularForPC(): Collection
    {
        $from = Carbon::now()->subMonths(3)->timestamp;
        $to = Carbon::now()->addMonths(3)->timestamp;

        $body = "
            fields name, cover.url, first_release_date, platforms.abbreviation, rating, follows;
            where platforms = (6)
            & (first_release_date >= {$from}
            & first_release_date < {$to})
            & follows != null;
            sort follows desc;
            limit 16;
        ";

        return $this->formatForList($this->fetch($body));
    }

    public function popularForPS4(): Collection
    {
        $from = Carbon::now()->subMonths(3)->timestamp;
        $to = Carbon::now()->addMonths(3)->timestamp;

        $body = "
            fields name, cover.url, first_release_date, platforms.abbreviation, rating, follows;
            where platforms = (48)
            & (first_release_date >= {$from}
            & first_release_date < {$to})
            & follows != null;
            sort follows desc;
            limit 16;
        ";

        return $this->formatForList($this->fetch($body));
    }

    public function popularForXONE(): Collection
    {
        $from = Carbon::now()->subMonths(3)->timestamp;
        $to = Carbon::now()->addMonths(3)->timestamp;

        $body = "
            fields name, cover.url, first_release_date, platforms.abbreviation, rating, follows;
            where platforms = (49)
            & (first_release_date >= {$from}
            & first_release_date < {$to})
            & follows != null;
            sort follows desc;
            limit 16;
        ";

        return $this->formatForList($this->fetch($body));
    }

    public function search(string $keyword): Collection
    {
        $body = "
            fields name, cover.url, first_release_date, platforms.abbreviation, rating;
            search \"{$keyword}\";
            limit 40;
        ";

        return $this->formatForList($this->fetch($body));
    }

    public function game(string $id)
    {
        $body = "
            fields name, summary, storyline, genres.name, cover.url, first_release_date, platforms.abbreviation, rating;
            where id = {$id};
        ";

        return $this->formatForDetails($this->fetch($body));
    }

    protected function fetch(string $body): array
    {
        $accessToken = $this->fetchAccessToken();

        return Http::withHeaders([
            'Client-ID' => config('app.client_id'),
        ])
            ->acceptJson()
            ->withToken($accessToken)
            ->send('POST', $this->endpoint, [
                'body' => $body
            ])
            ->json();
    }

    protected function fetchAccessToken(): string
    {
        if ($accessToken = Cache::get('igdb.access.token', false)) {
            return $accessToken;
        }

        $response = Http::post("https://id.twitch.tv/oauth2/token?client_id={$this->clientId}&client_secret={$this->clientSecret}&grant_type=client_credentials")
            ->json();

        if (!isset($response['access_token'], $response['expires_in'])) {
            throw new \Exception('Malformed response retrieving access token form Twitch');
        }

        Cache::put('igdb.access.token', (string)$response['access_token'], (int)$response['expires_in']);

        return (string)$response['access_token'];
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
