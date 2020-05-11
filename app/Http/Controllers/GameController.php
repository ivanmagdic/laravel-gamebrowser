<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use MarcReichel\IGDBLaravel\Builder;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $builder = new Builder('games');

        if ($searchFilter = $request->get('filter')) {
            $builder->search($searchFilter);
        }

        $builder->with(['cover' => ['url'], 'platforms' => ['id', 'name']]);

        if ($platform = $request->query('platform')) {
            switch ($platform) {
                case 'pc':
                    $builder->whereIn('platforms', [6, 14]); // 14 is Mac
                    break;
                case 'ps4':
                    $builder->whereIn('platforms', [45, 48]);
                    break;
                case 'xbox-one':
                    $builder->whereIn('platforms', [49]);
                    break;
            }
        }

        $builder->take(config('igdb.per_page_limit'));
        $games = $builder->orderByDesc('popularity')->get();

        foreach ($games as $game) {
            if (isset($game->cover->url)) {
                $game->image = str_replace('t_thumb', 't_cover_big', $game->cover->url);
            } else {
                $game->image = 'https://via.placeholder.com/150';
            }

            if (isset($game->rating)) {
                $game->rating = $game->rating / 20;
            }

            if (isset($game->first_release_date)) {
                $game->first_release_date = Carbon::createFromTimestamp($game->first_release_date)->year;
            }
        }

        return Inertia::render('Games/Index', [
            'games' => $games,
            'search' => $searchFilter ?? '',
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $builder = new Builder('games');

        $builder->where('id', $id);
        $game = $builder->with(['cover' => ['url'], 'platforms' => ['name'], 'genres' => ['name']])->firstOrFail();

        if (isset($game->cover->url)) {
            $game->image = str_replace('t_thumb', 't_cover_big', $game->cover->url);
        } else {
            $game->image = 'https://via.placeholder.com/150';
        }

        if (isset($game->rating)) {
            $game->rating = $game->rating / 20;
        }

        if (isset($game->first_release_date)) {
            $game->release_date = Carbon::createFromTimestamp($game->first_release_date)->toFormattedDateString();
        }

        return Inertia::render('Games/Show', [
            'game' => $game,
        ]);
    }

}
