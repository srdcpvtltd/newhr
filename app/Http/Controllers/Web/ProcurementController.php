<?php

namespace App\Http\Controllers\Web;

use App\Exports\AssetAssignmentListExport;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Repositories\AssetAssignmentRepository;
use App\Repositories\UserRepository;
use App\Services\AssetManagement\AssetService;
use App\Services\AssetManagement\AssetTypeService;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProcurementController extends Controller
{
    protected $view = 'admin.procurement.';

    public function __construct(
        private AssetTypeService $assetTypeService,
        private AssetService $assetService,
        private UserRepository $userRepo,
        private AssetAssignmentRepository $assetAsignmentRepo
    ) {
    }
    public function index(Request $request)
    {
        // dd($request->all());
        $this->authorize('list_type');
        try {
            $filterParameters = [
                'name' => $request->name ?? null,
                'assign_date' => $request->purchased_from ?? null,
                'return_date' => $request->purchased_to ?? null,
                'damaged' => $request->damaged ?? null,
                'type' => $request->type ?? null,
                'return_status' =>  $request->return_status ?? null,
                'download_excel' => $request->download_excel ?? null
            ];
            $select = ['*'];
            $with = ['assets', 'users', 'asset_types'];
            $assetType = $this->assetTypeService->getAllAssetTypes(['id', 'name']);
            $assetLists = $this->assetAsignmentRepo->getAllAssetAssignments($filterParameters, $select, $with);

            if ($filterParameters['download_excel']) {
                unset($filterParameters['download_excel']);
                return Excel::download(new AssetAssignmentListExport($filterParameters), 'Asset-assignment-report.xlsx');
            }

            return view($this->view . 'index', compact('assetLists', 'assetType', 'filterParameters'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
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

    public function store(Request $request){
        dd($request->all());
    }
}
