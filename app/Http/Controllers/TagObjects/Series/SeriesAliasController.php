<?php

namespace App\Http\Controllers\TagObjects\Series;

use App\Http\Controllers\TagObjects\TagObjectAliasController;
use Illuminate\Http\Request;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;
use App\Http\Requests\TagObjects\Series\Alias\StoreSeriesAliasRequest;

class SeriesAliasController extends TagObjectAliasController
{
	public function __construct()
    {
		parent::__construct();
		
		$this->paginationKey = "pagination_series_aliases_per_page_index";
		
		$this->middleware('auth')->except('index');
		$this->middleware('permission:Create Personal Series Alias|Create Global Series Alias')->only(['create', 'store']);
		$this->middleware('permission:Delete Personal Series Alias|Delete Global Series Alias')->only('destroy');
	}
	
    public function index(Request $request)
    {
		$aliases = new SeriesAlias();
		return $this->GetAliasIndex($request, $aliases, $this->paginationKey, 'series');
    }

    public function store(StoreSeriesAliasRequest $request, Series $series)
    {
        $alias = new SeriesAlias();
		return $this->StoreAlias($request, $alias, $series, 'series_id', 'series', 'show_series');
    }

    public function destroy(SeriesAlias $seriesAlias)
    {
		$this->authorize($seriesAlias);
        return $this->DeleteAlias($seriesAlias, 'series_id', 'series', 'show_series');
    }
}
