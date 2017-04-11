<?php

namespace App\Http\Controllers\TagObjects\Scanalator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Input;
use App\Models\TagObjects\Scanalator\Scanalator;
use App\Models\TagObjects\Scanalator\ScanalatorAlias;

class ScanalatorController extends Controller
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
	
		$scanalators = null;
		$scanalator_list_type = trim(strtolower($request->input('type')));
		$scanalator_list_order = trim(strtolower($request->input('order')));
		
		if (($scanalator_list_type != "usage") && ($scanalator_list_type != "alphabetic"))
		{
			$scanalator_list_type = "usage";
		}
		
		if (($scanalator_list_order != "asc") && ($scanalator_list_order != "desc"))
		{
			if($scanalator_list_type == "usage")
			{
				$scanalator_list_order = "asc";
			}
			else
			{
				$scanalator_list_order = "desc";
			}
		}
		
		if ($scanalator_list_type == "alphabetic")
		{
			$scanalators = new Scanalator();
			$scanalator_output = $scanalators->orderBy('name', $scanalator_list_order)->paginate(30);
			
			$scanalators = $scanalator_output;
		}
		else
		{	
			$scanalators = new Scanalator();
			$scanalators_used = $scanalators->join('chapter_scanalator', 'scanalators.id', '=', 'chapter_scanalator.scanalator_id')->select('scanalators.*', DB::raw('count(*) as total'))->groupBy('name')->orderBy('total', $scanalator_list_order)->orderBy('name', 'desc')->paginate(30);
			
			//Leaving this code commented outhere until the paginator handling for union gets fixed in Laravel (this adds scanalators that aren't used into the dataset used for popularity)
			
			/*$scanalators_not_used = $scanalators->leftjoin('chapter_scanalator', 'scanalators.id', '=', 'chapter_scanalator.scanalator_id')->where('collection_id', '=', null)->select('scanalators.*', DB::raw('0 as total'))->groupBy('name');
			
			$scanalator_output = $scanalators_used->union($scanalators_not_used)->orderBy('total', $scanalator_list_order)->orderBy('name', 'desc')->get();*/
			
			$scanalators = $scanalators_used;
		}		
		
		return View('scanalators.index', array('scanalators' => $scanalators->appends(Input::except('page')), 'list_type' => $scanalator_list_type, 'list_order' => $scanalator_list_order, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
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
		
		return View('scanalators.create', array('flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'name' => 'required|unique:scanalators',
			'url' => 'URL',
		]);
		
		$scanalator = new Scanalator();
		$scanalator->name = trim(Input::get('name'));
		$scanalator->description = trim(Input::get('description'));
		$scanalator->url = trim(Input::get('url'));
		$scanalator->created_by = Auth::user()->id;
		$scanalator->updated_by = Auth::user()->id;
		
		//Destroy any scanalator aliases that share the name with the artist to be created.
		$aliases_list = ScanalatorAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->destroy();
		}
		
		$scanalator->save();
		
		//Redirect to the scanalator that was created
		return redirect()->action('TagObjects\Scanalator\ScanalatorController@show', [$scanalator])->with("flashed_success", array("Successfully created scanalator $scanalator->name."));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Scanalator $scanalator)
    {
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		$global_list_order = trim(strtolower($request->input('global_order')));
		$personal_list_order = trim(strtolower($request->input('personal_order')));
		
		if (($global_list_order != 'asc') && ($global_list_order != 'desc'))
		{
			$global_list_order = 'asc';
		}
		
		if (($personal_list_order != 'asc') && ($personal_list_order != 'desc'))
		{
			$personal_list_order = 'asc';
		}
		
		$global_aliases = $scanalator->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate(10, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $scanalator->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate(10, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('scanalators.show', array('scanalator' => $scanalator, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Scanalator $scanalator)
    {
        $flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		$global_list_order = trim(strtolower($request->input('global_order')));
		$personal_list_order = trim(strtolower($request->input('personal_order')));
		
		if (($global_list_order != 'asc') && ($global_list_order != 'desc'))
		{
			$global_list_order = 'asc';
		}
		
		if (($personal_list_order != 'asc') && ($personal_list_order != 'desc'))
		{
			$personal_list_order = 'asc';
		}
		
		$global_aliases = $scanalator->aliases()->where('user_id', '=', null)->orderBy('alias', $global_list_order)->paginate(10, ['*'], 'global_alias_page');
		$global_aliases->appends(Input::except('global_alias_page'));
		
		$personal_aliases = null;
		
		if (Auth::check())
		{
			$personal_aliases = $scanalator->aliases()->where('user_id', '=', Auth::user()->id)->orderBy('alias', $personal_list_order)->paginate(10, ['*'], 'personal_alias_page');
			$personal_aliases->appends(Input::except('personal_alias_page'));
		}
		
		return View('scanalators.edit', array('tagObject' => $scanalator, 'global_list_order' => $global_list_order, 'personal_list_order' => $personal_list_order, 'global_aliases' => $global_aliases, 'personal_aliases' => $personal_aliases, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Scanalator $scanalator)
    {
		$this->validate($request, [
		'name' => ['required',
					Rule::unique('scanalators')->ignore($scanalator->id)],
				'url' => 'URL',
		]);
		
		$scanalator->name = trim(Input::get('name'));
		$scanalator->description = trim(Input::get('description'));
		$scanalator->url = trim(Input::get('url'));
		$scanalator->updated_by = Auth::user()->id;
		
		//Destroy any scanalator aliases that share the name with the artist to be created.
		$aliases_list = ScanalatorAlias::where('alias', '=', trim(Input::get('name')))->get();
		
		foreach ($aliases_list as $alias)
		{
			$alias->destroy();
		}
		
		$scanalator->save();
		
		//Redirect to the scanalator that was created
		return redirect()->action('TagObjects\Scanalator\ScanalatorController@show', [$scanalator])->with("flashed_success", array("Successfully updated scanalator $scanalator->name."));
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
