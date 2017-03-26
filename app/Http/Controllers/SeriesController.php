<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use App\Models\TagObjects\Series\Series;

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
		$flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
	
		$series = null;
		$series_list_type = trim(strtolower($request->input('type')));
		$series_list_order = trim(strtolower($request->input('order')));
		
		if (($series_list_type != "usage") && ($series_list_type != "alphabetic"))
		{
			$series_list_type = "usage";
		}
		
		if (($series_list_order != "asc") && ($series_list_order != "desc"))
		{
			if($series_list_type == "usage")
			{
				$series_list_order = "asc";
			}
			else
			{
				$series_list_order = "desc";
			}
		}
		
		if ($series_list_type == "alphabetic")
		{
			$series = new Series();
			$series_output = $series->orderBy('name', $series_list_order)->paginate(30);
			
			$series = $series_output;
		}
		else
		{	
			$series = new Series();
			$series_used = $series->join('collection_series', 'series.id', '=', 'collection_series.series_id')->select('series.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $series_list_order)->orderBy('name', 'desc')->paginate(30);
			
			//Leaving this code commented outhere until the paginator handling for union gets fixed in Laravel (this adds series that aren't used into the dataset used for popularity)
			
			/*$series_not_used = $series->leftjoin('collection_series', 'series.id', '=', 'collection_series.series_id')->where('collection_id', '=', null)->select('series.*', DB::raw('0 as total'))->groupBy('name');
			
			$series_output = $series_used->union($series_not_used)->orderBy('total', $series_list_order)->orderBy('name', 'desc')->get();*/
			
			$series = $series_used;
		}		
		
		return View('series.index', array('series' => $series->appends(Input::except('page')), 'list_type' => $series_list_type, 'list_order' => $series_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		return View('series.create', array('flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'name' => 'required|unique:series',
			'url' => 'URL',
		]);
		
		$series = new Series();
		$series->name = trim(Input::get('name'));
		$series->description = trim(Input::get('description'));
		$series->url = trim(Input::get('url'));
		$series->created_by = Auth::user()->id;
		$series->updated_by = Auth::user()->id;
		
		$series->save();
		
		//Redirect to the series that was created
		return redirect()->action('SeriesController@show', [$series])->with("flashed_success", array("Successfully created series $series->name."));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Series $series)
    {
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		$characters_list_type = trim(strtolower($request->input('character_type')));
		$characters_list_order = trim(strtolower($request->input('character_order')));
		
		if (($characters_list_type != "usage") && ($characters_list_type != "alphabetic"))
		{
			$characters_list_type = "usage";
		}
		
		if (($characters_list_order != "asc") && ($characters_list_order != "desc"))
		{
			if($characters_list_type == "usage")
			{
				$characters_list_order = "asc";
			}
			else
			{
				$characters_list_order = "desc";
			}
		}
		
		if ($characters_list_type == "alphabetic")
		{
			$characters = $series->characters();
			$characters_output = $characters->orderBy('name', $characters_list_order)->paginate(12, ['*'], 'character_page');

			$characters = $characters_output;
		}
		else
		{	
			$characters = $series->characters()	;
			$characters_used = $characters->join('character_collection', 'characters.id', '=', 'character_collection.character_id')->select('characters.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $characters_list_order)->orderBy('name', 'desc')->paginate(12, ['*'], 'character_page');
			
			//Leaving this code commented outhere until the paginator handling for union gets fixed in Laravel (this adds series that aren't used into the dataset used for popularity)
			
			/*$characters_not_used = $characters->leftjoin('character_collection', 'characters.id', '=', 'character_collection.character_id')->where('collection_id', '=', null)->select('characters.*', DB::raw('0 as total'))->groupBy('name');
			
			$characters_output = $characters_used->union($characters_not_used)->orderBy('total', $characters_list_order)->orderBy('name', 'desc')->get();*/
			
			$characters = $characters_used;
		}
		
		return View('series.show', array('series' => $series, 'characters' => $characters->appends(Input::except('character_page')), 'character_list_type' => $characters_list_type, 'character_list_order' => $characters_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }
	
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Series $series)
    {
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		return View('series.edit', array('tagObject' => $series, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Series $series)
    {
		$this->validate($request, [
		'name' => ['required',
					Rule::unique('series')->where(function ($query){
						$query->where('id', '!=', trim(Input::get('series_id')));
					})],
				'url' => 'URL',
		]);
		
		$series->name = trim(Input::get('name'));
		$series->description = trim(Input::get('description'));
		$series->url = trim(Input::get('url'));
		$series->updated_by = Auth::user()->id;
		
		$series->save();
		
		//Redirect to the series that was created
		return redirect()->action('SeriesController@show', [$series])->with("flashed_success", array("Successfully updated series $series->name."));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
