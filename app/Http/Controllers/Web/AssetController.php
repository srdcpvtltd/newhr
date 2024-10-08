<?php

namespace App\Http\Controllers\Web;

use Exception;
use Illuminate\Http\Request;
use App\Exports\AssetListExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\BrandRepository;
use App\Services\AssetManagement\AssetService;
use App\Services\AssetManagement\AssetTypeService;
use App\Requests\AssetManagement\AssetDetailRequest;


class AssetController extends Controller
{
    private $view = 'admin.assetManagement.assetDetail.';

    public function __construct(
        private AssetService $assetService,
        private AssetTypeService $assetTypeService,
        private UserRepository $userRepo,
        private BrandRepository $brandRepo
    ) {
    }

    public function index(Request $request)
    {
        $this->authorize('list_assets');
        try {
            $filterParameters = [
                'name' => $request->name ?? null,
                'purchased_from' => $request->purchased_from ?? null,
                'purchased_to' => $request->purchased_to ?? null,
                'is_working' => $request->is_working ?? null,
                'is_available' => $request->is_available ?? null,
                'type' => $request->type ?? null,
                'download_excel' => $request->download_excel ?? null
            ];
            $select = ['*'];
            $with = ['type:id,name', 'assignedTo:id,name'];
            $assetType = $this->assetTypeService->getAllAssetTypes(['id', 'name']);
            $assetLists = $this->assetService->getAllAssetsPaginated($filterParameters, $select, $with);
            if ($filterParameters['download_excel']) {
                unset($filterParameters['download_excel']);
                return Excel::download(new AssetListExport($filterParameters), 'Asset-report.xlsx');
            }
            return view($this->view . 'index', compact('assetLists', 'assetType', 'filterParameters'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create_assets');
        try {
            $employeeSelect = ['id', 'name'];
            $typeSelect = ['id', 'name'];
            $assetType = $this->assetTypeService->getAllActiveAssetTypes($typeSelect);
            $brands = $this->brandRepo->getBrandlist(['id', 'name']);
            $employees = $this->userRepo->getAllVerifiedEmployeeOfCompany($employeeSelect);
            return view($this->view . 'create', compact('assetType', 'employees','brands'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function store(AssetDetailRequest $request)
    {
        $this->authorize('create_assets');
        try {
            $validatedData = $request->validated();
            $this->assetService->saveAssetDetail($validatedData);
            return redirect()->route('admin.assets.index')->with('success', 'Asset record saved successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function show($id)
    {
        $this->authorize('show_asset');
        try {
            $select = ['*'];
            $with = ['type:id,name', 'assignedTo:id,name'];
            $assetDetail = $this->assetService->findAssetById($id, $select, $with,);
            return view($this->view . 'show', compact('assetDetail'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
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
            $brands = $this->brandRepo->getBrandlist(['id', 'name']);
            $assetDetail = $this->assetService->findAssetById($id);
            return view($this->view . 'edit', compact('assetDetail','brands', 'assetType', 'employees'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(AssetDetailRequest $request, $id)
    {
        $this->authorize('edit_assets');
        try {
            $validatedData = $request->validated();
            $this->assetService->updateAssetDetail($id, $validatedData);
            return redirect()->route('admin.assets.index')
                ->with('success', 'Asset Detail Updated Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage())
                ->withInput();
        }
    }

    public function delete($id)
    {
        $this->authorize('delete_assets');
        try {
            DB::beginTransaction();
            $this->assetService->deleteAsset($id);
            DB::commit();
            return redirect()->back()->with('success', 'Asset Detail Deleted Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function changeAvailabilityStatus($id)
    {
        $this->authorize('edit_assets');
        try {
            $this->assetService->toggleAvailabilityStatus($id);
            return redirect()->back()->with('success', 'Status Changed  Successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
