<?php

namespace App\Http\Controllers\TagObjects\Series;

use App\Http\Controllers\TagObjects\TagObjectAliasController;
use Illuminate\Http\Request;
use App\Models\TagObjects\Series\Series;
use App\Models\TagObjects\Series\SeriesAlias;
use App\Http\Requests\TagObjects\Aliases\StoreSeriesAliasRequest;

class SeriesAliasController extends TagObjectAliasController
{
    public function index(Request $request)
    {
		$aliases = new SeriesAlias();
		return self::GetAliasIndex($request, $aliases, 'seriesAliasesPerPageIndex', 'series');
    }

    public function store(StoreSeriesAliasRequest $request, Series $series)
    {
        $alias = new SeriesAlias();
		return self::StoreAlias($request, $alias, $series, 'series_id', 'series', 'show_series');
    }

    public function destroy(SeriesAlias $seriesAlias)
    {
		$this->authorize($seriesAlias);
        return self::DeleteAlias($seriesAlias, 'series_id', 'series', 'show_series');
    }
}
