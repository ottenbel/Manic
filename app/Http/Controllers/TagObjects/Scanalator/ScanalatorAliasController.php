<?php

namespace App\Http\Controllers\TagObjects\Scanalator;

use App\Http\Controllers\TagObjects\TagObjectAliasController;
use Illuminate\Http\Request;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;
use App\Http\Requests\TagObjects\Scanalator\Alias\StoreScanalatorAliasRequest;

class ScanalatorAliasController extends TagObjectAliasController
{
	public function __construct()
    {
		$this->middleware('auth')->except('index');
		$this->middleware('permission:Create Personal Scanalator Alias|Create Global Scanalator Alias')->only(['create', 'store']);
		$this->middleware('permission:Delete Personal Scanalator Alias|Delete Global Scanalator Alias')->only('destroy');
	}
	
    public function index(Request $request)
    {
		$aliases = new ScanalatorAlias();
		return self::GetAliasIndex($request, $aliases, 'scanalatorAliasesPerPageIndex', 'scanalators');
    }

    public function store(StoreScanalatorAliasRequest $request, Scanalator $scanalator)
    {
		$alias = new ScanalatorAlias();
		return self::StoreAlias($request, $alias, $scanalator, 'scanalator_id', 'scanalator', 'show_scanalator');
    }
	
    public function destroy(ScanalatorAlias $scanalatorAlias)
    {
		$this->authorize($scanalatorAlias);
        return self::DeleteAlias($scanalatorAlias, 'scanalator_id', 'scanalator', 'show_scanalator');
    }
}
