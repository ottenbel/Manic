<?php

namespace App\Http\Controllers\API\V1\Collection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use Input;
use App\Models\TagObjects\Artist\Artist;
use App\Models\TagObjects\Character\Character;
use App\Models\Collection\Collection;
use App\Models\Image;
use App\Models\Language;
use App\Models\Rating;
use App\Models\TagObjects\Series\Series;
use App\Models\Status;
use App\Models\TagObjects\Tag\Tag;

class CollectionGeneralAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
		//
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
		//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		//
    }
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Collection $collection)
    {
		//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Collection $collection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Collection $collection)
    {
		//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Collection $collection)
    {
        //
    }
}
