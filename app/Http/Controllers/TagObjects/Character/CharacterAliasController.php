<?php

namespace App\Http\Controllers\TagObjects\Character;

use App\Http\Controllers\TagObjects\TagObjectAliasController;
use Illuminate\Http\Request;
use App\Models\TagObjects\Character\Character;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Http\Requests\TagObjects\Character\Alias\StoreCharacterAliasRequest;

class CharacterAliasController extends TagObjectAliasController
{
	public function __construct()
    {
		parent::__construct();
		
		$this->paginationKey = "pagination_character_aliases_per_page_index";
		
		$this->middleware('auth')->except('index');
		$this->middleware('permission:Create Personal Character Alias|Create Global Character Alias')->only(['create', 'store']);
		$this->middleware('permission:Delete Personal Character Alias|Delete Global Character Alias')->only('destroy');
	}
	
    public function index(Request $request)
    {
		$aliases = new CharacterAlias();
		return $this->GetAliasIndex($request, $aliases, $this->paginationKey, 'characters');
    }
	
    public function store(StoreCharacterAliasRequest $request, Character $character)
    {	
		$alias = new CharacterAlias();
		return $this->StoreAlias($request, $alias, $character, 'character_id', 'character', 'show_character');
    }

    public function destroy(CharacterAlias $characterAlias)
    {
		$this->authorize($characterAlias);
        return $this->DeleteAlias($characterAlias, 'character_id', 'character', 'show_character');
    }
}
