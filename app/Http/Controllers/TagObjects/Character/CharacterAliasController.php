<?php

namespace App\Http\Controllers\TagObjects\Character;

use App\Http\Controllers\TagObjects\TagObjectAliasController;
use Illuminate\Http\Request;
use App\Models\TagObjects\Character\Character;
use App\Models\TagObjects\Character\CharacterAlias;
use App\Http\Requests\TagObjects\Aliases\StoreCharacterAliasRequest;

class CharacterAliasController extends TagObjectAliasController
{
    public function index(Request $request)
    {
		$aliases = new CharacterAlias();
		return self::GetAliasIndex($request, $aliases, 'characterAliasesPerPageIndex', 'characters');
    }
	
    public function store(StoreCharacterAliasRequest $request, Character $character)
    {	
		$alias = new CharacterAlias();
		return self::StoreAlias($request, $alias, $character, 'character_id', 'character', 'show_character');
    }

    public function destroy(CharacterAlias $characterAlias)
    {
		$this->authorize($characterAlias);
        return self::DeleteAlias($characterAlias, 'character_id', 'character', 'show_character');
    }
}
