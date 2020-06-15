<?php

namespace App\Http\Controllers;

use App\IGDB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

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
        $igdb = new IGDB(config('app.igdb_api_key'));

        $games = $igdb->mostAnticipated();

        if ($searchFilter = $request->get('filter')) {
            $games = $igdb->search($searchFilter);
        }

        if ($platform = $request->query('platform')) {
            switch ($platform) {
                case 'pc':
                    $games = $igdb->popularForPC();
                    break;
                case 'ps4':
                    $games = $igdb->popularForPS4();
                    break;
                case 'xbox-one':
                    $games = $igdb->popularForXONE();
                    break;
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
        $igdb = new IGDB(config('app.igdb_api_key'));

        return Inertia::render('Games/Show', [
            'game' => $igdb->game($id),
        ]);
    }

}
