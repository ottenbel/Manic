<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Language;
use Config;
use ConfigurationLookupHelper;
use DB;
use App\Http\Requests\Language\StoreLanguageRequest;
use App\Http\Requests\Language\UpdateLanguageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Input;

class LanguageController extends WebController
{
	public function __construct()
    {
		parent::__construct();
		
		$this->paginationKey = "pagination_languages_per_page_index";
		$this->placeholderStub = "language";
		$this->placeheldFields = array('name','description', 'url');
		
		$this->middleware('auth')->except(['index', 'show']);
		$this->middleware('permission:Create Language')->only(['create', 'store']);
		$this->middleware('permission:Edit Language')->only(['edit', 'update']);
		$this->middleware('permission:Delete Language')->only('destroy');
	}
	
    public function index(Request $request)
    {
		$order = trim(strtolower($request->input('order')));
		if (($order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
			&& ($order != Config::get('constants.sortingStringComparison.listOrder.descending')))
		{
			$order = Config::get('constants.sortingStringComparison.listOrder.ascending');
		}
		
		$this->GetFlashedMessages($request);
		$paginationLanguagesPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->paginationKey)->value;
		
		$languages = new Language();
		$languages = $languages->orderBy('name', $order)->paginate($paginationLanguagesPerPageIndexCount);
		$languages->appends(Input::except('page'));
		
		return View('languages.index', array('languages' => $languages, 'list_order' => $order, 'messages' => $this->messages));
    }
	
    public function create(Request $request)
    {
        $this->authorize(Language::class);
		$this->GetFlashedMessages($request);
		$configurations = $this->GetConfiguration();
		return View('languages.create', array('configurations' => $configurations, 'messages' => $this->messages));
    }
	
    public function store(StoreLanguageRequest $request)
    {
		$language = new Language();
		return $this->InsertOrUpdate($request, $language, 'created', 'create');
    }
	
    public function show(Request $request, Language $language)
    {
        $this->GetFlashedMessages($request);
		$usageCount = $language->collections()->count();
		
		return View('languages.show', array('language' => $language, 'usageCount' => $usageCount, 'messages' => $this->messages));
    }

    public function edit(Request $request, Language $language)
    {
        $this->authorize($language);
		$this->GetFlashedMessages($request);
		$configurations = $this->GetConfiguration();
		
		return View('languages.edit', array('configurations' => $configurations, 'language' => $language, 'messages' => $this->messages));
    }

    public function update(UpdateLanguageRequest $request, Language $language)
    {
		return $this->InsertOrUpdate($request, $language, 'updated', 'update');
    }
    
    public function destroy(Language $language)
    {
        $this->authorize($language);
		$languageName = $language->name;
		
		DB::beginTransaction();
		try
		{
			//Force deleting for now, build out functionality for soft deleting later.
			$language->forceDelete();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully delete language $languageName.", ['language' => $language->id, 'error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully purged language $languageName from the database.");
		return redirect()->route('index_language')->with("messages", $this->messages);
    }
	
	private function InsertOrUpdate($request, $language, $action, $errorAction)
	{
		DB::beginTransaction();
		try
		{
			$language->fill($request->only(['name', 'description', 'url']));	
			$language->save();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully $errorAction language $language->name.", ['language' => $language->id, 'error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully $action language $language->name.");
		return redirect()->route('show_language', ['language' => $language])->with("messages", $this->messages);
	}
}
