<?php

namespace App\Http\Controllers\TagObjects\Artist;

use App\Http\Controllers\TagObjects\TagObjectAliasController;
use Illuminate\Http\Request;
use App\Models\TagObjects\Artist\Artist;
use App\Models\TagObjects\Artist\ArtistAlias;
use App\Http\Requests\TagObjects\Artist\Alias\StoreArtistAliasRequest;

class ArtistAliasController extends TagObjectAliasController
{
	public function __construct()
    {
		parent::__construct();
		
		$this->paginationKey = "pagination_artist_aliases_per_page_index";
		
		$this->middleware('auth')->except('index');
		$this->middleware('permission:Create Personal Artist Alias|Create Global Artist Alias')->only(['create', 'store']);
		$this->middleware('permission:Delete Personal Artist Alias|Delete Global Artist Alias')->only('destroy');
	}
	
    public function index(Request $request)
    {	
		$aliases = new ArtistAlias();
		return $this->GetAliasIndex($request, $aliases, $this->paginationKey, 'artists');
    }

    public function store(StoreArtistAliasRequest $request, Artist $artist)
    {
		$alias = new ArtistAlias();
		return $this->StoreAlias($request, $alias, $artist, 'artist_id', 'artist', 'show_artist');
    }

    public function destroy(ArtistAlias $artistAlias)
    {
		$this->authorize($artistAlias);
		return $this->DeleteAlias($artistAlias, 'artist_id', 'artist', 'show_artist');
    }
}
