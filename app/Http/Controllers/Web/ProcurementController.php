<?php

namespace App\Http\Controllers\Web;

use Exception;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Exports\AssetAssignmentListExport;
use App\Repositories\ProcurementRepository;
use App\Services\AssetManagement\AssetService;
use App\Repositories\AssetAssignmentRepository;
use App\Repositories\BrandRepository;
use App\Requests\Procurement\ProcurementRequest;
use App\Services\Procurement\ProcurementService;
use App\Services\AssetManagement\AssetTypeService;
use Illuminate\Support\Facades\DB;

class ProcurementController extends Controller
{
    protected $view = 'admin.procurement.';

    public function __construct(
        private AssetTypeService $assetTypeService,
        private AssetService $assetService,
        private UserRepository $userRepo,
        private AssetAssignmentRepository $assetAsignmentRepo,
        private ProcurementRepository $procurementRepo,
        private ProcurementService $procurementService,
        private BrandRepository $brandRepo

    ) {}
    public function index(Request $request)
    {
        $this->authorize('list_type');
        try {
            $filterParameters = [
                'procurement_number' => $request->procurement_number ?? null,
                'user_id' => $request->user_id ?? null,
                'email' => $request->email ?? null,
                'asset_type_id' => $request->asset_type_id ?? null,
                'quantity' => $request->quantity ?? null,
                'amount' =>  $request->amount ?? null,
                'request_date' => $request->request_date ?? null,
                'delivery_date' => $request->delivery_date ?? null,
                'brand_id' => $request->asset_type_id ?? null,
                'download_excel' => $request->download_excel ?? null

            ];
            $select = ['*'];
            $with = ['users', 'asset_types', 'brands'];
            $assetType = $this->assetTypeService->getAllAssetTypes(['id', 'name']);
            // $brands = $this->brandRepo->getBrandlist(['id', 'name']);
            $requests = $this->procurementRepo->getAllRequests($filterParameters, $select, $with);


            if ($filterParameters['download_excel']) {
                unset($filterParameters['download_excel']);
                return Excel::download(new AssetAssignmentListExport($filterParameters), 'Asset-assignment-report.xlsx');
            }
            // dd($request->all());

            return view($this->view . 'index', compact('requests', 'assetType', 'filterParameters'));
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
            $brands = $this->brandRepo->getBrandlist(['id', 'name']);
            $procurement_number = $this->procurementRepo->getProcruementNumber();

            return view($this->view . 'create', compact('brands','assets', 'assetType', 'employees', 'procurement_number'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function store(ProcurementRequest $request)
    {
        try {
            $validated_data =  $request->validated();
            $this->procurementService->storeProcurement($validated_data);
            return redirect()->route($this->view . 'index')->with('success', 'Request Added Successfully');
        } catch (\Exception $e) {
            return  redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->authorize('edit_assets');
        try {
            $employeeSelect = ['id', 'name'];
            $typeSelect = ['id', 'name'];
            $assetType = $this->assetTypeService->getAllActiveAssetTypes($typeSelect);
            $employees = $this->userRepo->getAllVerifiedEmployeeOfCompany($employeeSelect);
            $procurementDetail = $this->procurementService->findProcurementById($id);
            return view($this->view . 'edit', compact('procurementDetail', 'assetType', 'employees'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(ProcurementRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $this->procurementService->updateProcurement($id, $validatedData);
            return redirect()->route('admin.procurement.index')
                ->with('success', 'Request Updated Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage())
                ->withInput();
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $this->procurementService->deleteProcurementRequest($id);
            DB::commit();
            return redirect()->back()->with('success', 'Request Deleted Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
