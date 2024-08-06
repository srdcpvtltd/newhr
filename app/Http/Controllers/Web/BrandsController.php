<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AssetManagement\AssetTypeService;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    private $view = 'admin.assetManagement.brands.';

    public function __construct(
        private AssetTypeService $assetTypeService
    ) {
    }

    public function index()
    {
        $this->authorize('list_type');
        try {
            $select = ['*'];
            $with = ['assets'];
            $assetTypeLists = $this->assetTypeService->getAllAssetTypes($select, $with);
            return view($this->view . 'index', compact('assetTypeLists'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
