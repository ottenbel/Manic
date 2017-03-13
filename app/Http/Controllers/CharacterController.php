<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use App\Character;
use App\Series;

class CharacterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
		$characters = null;
		$character_list_type = trim(strtolower($request->input('type')));
		$character_list_order = trim(strtolower($request->input('order')));
		
		if (($character_list_type != "usage") && ($character_list_type != "alphabetic"))
		{
			$character_list_type = "usage";
		}
		
		if (($character_list_order != "asc") && ($character_list_order != "desc"))
		{
			if($character_list_type == "usage")
			{
				$character_list_order = "asc";
			}
			else
			{
				$character_list_order = "desc";
			}
		}
		
		if ($character_list_type == "alphabetic")
		{
			$characters = new Character();
			$character_output = $characters->orderBy('name', $character_list_order)->paginate(30);
			
			$characters = $character_output;
		}
		else
		{	
			$characters = new Character();
			$characters_used = $characters->join('character_collection', 'characters.id', '=', 'character_collection.character_id')->select('characters.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $character_list_order)->orderBy('name', 'desc')->paginate(30);
			
			//Leaving this code commented outhere until the paginator handling for union gets fixed in Laravel (this adds characters that aren't used into the dataset used for popularity)
			
			/*$characters_not_used = $characters->leftjoin('character_collection', 'characters.id', '=', 'character_collection.character_id')->where('collection_id', '=', null)->select('characters.*', DB::raw('0 as total'))->groupBy('name');
			
			$character_output = $characters_used->union($characters_not_used)->orderBy('total', $character_list_order)->orderBy('name', 'desc')->get();*/
			
			$characters = $characters_used;
		}		
				
		$flashed_data = $request->session()->get('flashed_data');
		
		return View('characters.index', array('characters' => $characters->appends(Input::except('page')), 'list_type' => $character_list_type, 'list_order' => $character_list_order, 'flashed_data' => $flashed_data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Series $series = null)
    {
        $flashed_data = $request->session()->get('flashed_data');
		
		return View('characters.create', array('flashed_data' => $flashed_data, 'series' => $series));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {	
		$this->validate($request, [
		'name' => ['required',
					Rule::unique('characters')->where(function ($query){
						$query->where('id', '!=', trim(Input::get('character_id')));
					})],
				'url' => 'URL',
		]);
		
		$parent_series = Series::where('name', '=', trim(Input::get('parent_series')))->first();
		
		if ($parent_series == null)
		{
			return Redirect::back()->withErrors(['parent_series' => 'A character must have a valid parent series associated with it.'])->withInput();
		}
		
		$character = new Character();
		$character->name = trim(Input::get('name'));
		$character->description = trim(Input::get('description'));
		$character->url = trim(Input::get('url'));
		$character->series_id = $parent_series->id;
		$character->created_by = Auth::user()->id;
		$character->updated_by = Auth::user()->id;
		
		$parent_series->updated_by = Auth::user()->id;
		
		$character->save();
		$parent_series->save();
		
		//Redirect to the character that was created
		return redirect()->action('CharacterController@show', [$character])->with("flashed_data", "Successfully created character $character->name under series $parent_series->name.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Character $character)
    {
        $flashed_data = $request->session()->get('flashed_data');
		
		return View('characters.show', array('character' => $character, 'flashed_data' => $flashed_data));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Character $character)
    {
        $flashed_data = $request->session()->get('flashed_data');
		
		return View('characters.edit', array('tagObject' => $character, 'flashed_data' => $flashed_data));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Character $character)
    {
		$this->validate($request, [
		'name' => ['required',
					Rule::unique('characters')->where(function ($query){
						$query->where('id', '!=', trim(Input::get('character_id')));
					})],
				'url' => 'URL',
		]);
		
		$character->name = trim(Input::get('name'));
		$character->description = trim(Input::get('description'));
		$character->url = trim(Input::get('url'));
		$character->updated_by = Auth::user()->id;
		
		$character->save();
		
		//Redirect to the character that was created
		return redirect()->action('CharacterController@show', [$character])->with("flashed_data", "Successfully updated character $character->name.");
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