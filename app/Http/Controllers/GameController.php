<?php

namespace App\Http\Controllers;

use App\IGDB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param IGDB $igdb
     * @return Response
     */
    public function index(Request $request, IGDB $igdb)
    {
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
     * @param IGDB $igdb
     * @return Response
     */
    public function show($id, IGDB $igdb)
    {
        return Inertia::render('Games/Show', [
            'game' => $igdb->game($id),
        ]);
    }

}
