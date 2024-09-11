<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CrmEnquery;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Repositories\BranchRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\EmployeeLeaveTypeRepository;
use App\Repositories\LeaveTypeRepository;
use App\Repositories\OfficeTimeRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserAccountRepository;
use App\Repositories\UserRepository;
use Exception;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CrmEnqueriesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $crmenquery = CrmEnquery::all();
        return view('crmenquery.create');
        // return view('crmenquery.index')->with('crmenquery', $crmenquery);
    }

    public function list()
    {
        $role = Role::all();
        $admin = User::where('role_id',$role);
        // dd($admin);
        $user = Auth::user();
        if (auth()->user()->role_id == $user->id) {
             $crmenquery = CrmEnquery::paginate(5);
             $isAdmin = true;
        }
        else {
            $isAdmin = false;
            $crmenquery = CrmEnquery::where('assign_user',$user->id)->paginate(5);
        }
       
        
        // dd($crmenquery);
        return view('admin.crmenquery.index', ['crmenquery'=>$crmenquery, 'isAdmin'=>$isAdmin]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('crmenquery.create');
    }

    /*
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required|min:15',
            'email'=>'required|min:15',
            'number'=>'required|min:12',
            'address'=>'required|min:20',
            'message'=>'required|min:100',
        ];
        $validator = Validator::make($request->all(),$rules);

        $crmenquery = new CrmEnquery();
        $crmenquery->name = $request->name;
        $crmenquery->email = $request->email;
        $crmenquery->number = $request->number;
        $crmenquery->address = $request->address;
        $crmenquery->message = $request->message;
        // $crmenquery->message = $request->message;
        $crmenquery->save();
        return redirect()->route('crmenquery.index')->with('success', 'CRM Enquery created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
{
    // $id = $enquireId;
    // $enquire = CrmEnquery::where('id', $enquireId)->first();
    $enquire = CrmEnquery::findOrFail($id);
   
    if (!$enquire) {
        return response()->json(['error' => 'Enquire not found'], 404);
    }
    return response()->json([
        'name' => $enquire->name,
        'email' => $enquire->email,
        'number' => $enquire->number,
        'address' => $enquire->address,
        'message' => $enquire->message,
    ]);
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_crm($id)
    {
        $crmenquery = CrmEnquery::find($id);
        // $crmenqueryl = Crmenquery::with('assignedUser')->get();
        $branch  = Branch::all();
        $departments  = Department::all();
        $users = User::all();
    return view('admin.crmenquery.edit', ['crmenquery'=>$crmenquery, 'users' => $users, 'branch' => $branch, 'departments' => $departments]);
    }


    public function getUsersByDepartment($departmentId)
    {
        $users = User::where('department_id', $departmentId)->get();
        return response()->json($users);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $crmenquery = CrmEnquery::find($id);
        // $users  = User::find($id);
        $assignUserId = $request->input('assign_user');
    $crmenquery->name = $request->name;
    $crmenquery->email = $request->email;
    $crmenquery->number = $request->number;
    $crmenquery->address = $request->address;
    $crmenquery->message = $request->message;
    $crmenquery->assign_user = $assignUserId;
    // $crmenquery->assign = $assignuser->id;
    // $crmenquery->assign_user = $users->id;
    $crmenquery->save();
    return redirect()->route('admin.crmenquery.index')->with('success', 'CRM Enquery updated/Assign successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

   
}
