<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LeadAgent;
use App\Models\LeadCategory;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Http\Request;

class LeadsSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lead Sources
        $leadSources = LeadSource::paginate(10);

        // Lead Status
        $leadStatus = LeadStatus::paginate(10);

        // Lead Agent
        $leadAgent = LeadAgent::paginate(10);

        // Lead Category
        $leadCategory = LeadCategory::paginate(10);

        $users = User::whereNotIn('id', LeadAgent::pluck('userid'))->get();
        return view('admin.leadsSetting.index', compact('leadSources','leadStatus', 'leadAgent', 'leadCategory', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function leadagent_create()
    {
        // $users = User::all();
        $users = User::whereNotIn('id', LeadAgent::pluck('userid'))->get();
        return view('admin.leadsSetting.leadAgent.modal', ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //  Lead Source
    public function leadSourceList()
    {
        // $leadSources = LeadSource::all();
        // $leadSources = LeadSource::paginate(10);
        // // return view('admin.leadsSetting.index', ['leadSources'=>$leadSources]);
        // return view('admin.leadsSetting.index', compact('leadSources'));
    }

    public function store(Request $request) // Store Lead Source
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:lead_sources',
        ]);

        // Create a new lead source
        LeadSource::create([
            'name' => $request->input('name'),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Lead Source added successfully!');

    }

    public function leadstatus_store(Request $request) // Store Lead Status
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:lead_statuses',
        ]);

        // Create a new lead source
        LeadStatus::create([
            'name' => $request->input('name'),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Lead Status added successfully!');

    }

    public function leadagent_store(Request $request) // Store Lead Status
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        // Get the selected user's ID and name
        $userId = $request->input('username');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found!');
        }

        // Create a new lead source
        LeadAgent::create([
            'userid' => $userId,
            'username' => $user->name,
            'is_deleted' => 0,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Lead Agent Assign successfully!');

    }

    public function leadcategory_store(Request $request) // Store Lead Category
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:lead_categories',
        ]);

        // Create a new lead source
        LeadCategory::create([
            'name' => $request->input('name'),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Lead Source added successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) // lead source update
    {

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the lead source and update its name
        $leadSource = LeadSource::findOrFail($id);
        $leadSource->name = $request->input('name');
        $leadSource->save();
       

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Lead Source updated successfully!');

    }

    public function leadstatus_update(Request $request, $id) // lead source update
    {

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the lead source and update its name
        $leadStatus = LeadStatus::findOrFail($id);
        $leadStatus->name = $request->input('name');
        $leadStatus->save();
       

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Lead Status updated successfully!');

    }

    public function leadcategory_update(Request $request, $id) // lead source update
    {

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the lead source and update its name
        $leadCategory = LeadCategory::findOrFail($id);
        $leadCategory->update($request->all());
        

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Lead Category updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) // Delete Lead Source
    {
        // Find the lead source by ID
        $leadSource = LeadSource::findOrFail($id);

        // Delete the lead source
        $leadSource->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Lead Source deleted successfully!');
    }

    // Delete Lead Status
    public function leadstatus_destroy($id) // Delete Lead Source
    {
        // Find the lead source by ID
        $leadStatus = LeadStatus::findOrFail($id);

        // Delete the lead source
        $leadStatus->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Lead Status deleted successfully!');
    }

    // Delete Lead Agent
    public function leadAgentDelete(Request $request, $id)
    {
        // Find the lead agent by ID
        $leadAgent = LeadAgent::findOrFail($id);
        $leadAgent->delete();


        // Redirect back with a success message
        return redirect()->back()->with('success', 'Lead Agent deleted successfully!');
    }

    // Delete Lead Category
    public function leadcategory_destroy($id) // Delete Lead Source
    {
        // Find the lead source by ID
        $leadCategory = LeadCategory::findOrFail($id);

        // Delete the lead source
        $leadCategory->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Lead Source deleted successfully!');
    }

}
