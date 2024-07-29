<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Repositories\AssetAssignmentRepository;
use App\Repositories\UserRepository;
use App\Services\AssetManagement\AssetService;
use App\Services\AssetManagement\AssetTypeService;
use Exception;
use Illuminate\Http\Request;

class AssetAssignmentController extends Controller
{
    private $view = 'admin.assetManagement.assetAssignment.';

    public function __construct(
        private AssetTypeService $assetTypeService,
        private AssetService $assetService,
        private UserRepository $userRepo,
        private AssetAssignmentRepository $assetAsignmentRepo
    ) {
    }

    public function index()
    {
        $this->authorize('list_type');
        try {
            $select = ['*'];
            $with = ['assets', 'users'];
            $assetLists = $this->assetAsignmentRepo->getAllAssetAssignments($select, $with);
            // dd($assetLists);
            return view($this->view . 'index', compact('assetLists'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function return(Request $request)
    {
        $user_id = $request->user_id;
        $asset_id = $request->asset_id;
        $cost_of_damage = $request->cost_of_damage ? $request->cost_of_damage :  0;
        $amount_paid = $request->amount_paid ? $request->amount_paid :  null;
        $damage_reason = $request->damage_reason ? $request->damage_reason :  null;

        $q = AssetAssignment::where('user_id',$user_id)->where('asset_id',$asset_id);
        if($request->damage == 0){
            $result = $q->update([
                'return_date' => date('Y-m-d',strtotime($request->return_date)),
                'returned' => 1,
                'damaged' => $request->damage,
                'cost_of_damage' => null,
                'return_status' => 1
            ]);

            dd($result);
        }else{
            $result = $q->update([
                'return_date' => date('Y-m-d',strtotime($request->return_date)),
                'returned' => 1,
                'damaged' => $request->damage,
                'cost_of_damage' => $cost_of_damage,
                'paid' => $amount_paid ,
                'damage_reason' => $damage_reason,
                'return_status' => $amount_paid == 1 ? 1 : 0,
            ]);

            dd($result);

        }

    }

    public function create()
    {
        try {
            $employeeSelect = ['id', 'name'];
            $typeSelect = ['id', 'name'];

            $assets =  Asset::all(['id', 'name'])->toArray();
            $assetType = $this->assetTypeService->getAllActiveAssetTypes($typeSelect);
            $employees = $this->userRepo->getAllVerifiedEmployeeOfCompany($employeeSelect);
            return view($this->view . 'create', compact('assets', 'assetType', 'employees'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getAllAssetsByAssetTypeId($id)
    {

        $assets =  Asset::where('type_id', $id)->get(['id', 'name']);

        if ($assets) {
            return response()->json([
                'data' => $assets
            ]);
        } else {
            return response()->json([
                'data' => null
            ]);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $this->authorize('create_assets');
        try {
            AssetAssignment::create([
                'asset_type_id' => $request->type_id,
                'asset_id' => $request->asset,
                'user_id' => $request->assigned_to,
                'assign_date' => $request->assign_date
            ]);
            return redirect()->route('admin.asset_assignment.index')->with('success', 'Asset Assigned Successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
