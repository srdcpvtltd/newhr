<?php

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class BrandRepository
{

    public function getBrandlist()
    {
        return Brand::all();
    }

    public function storeBrands($data)
    {
        $brand = new Brand;
        $brand->name = $data['name'];
        $brand->save();
    }

    public function findBrandById($id)
    {
        $brand_details = Brand::find($id)->first();

        return $brand_details;
    }

    public function updateBrandDetails($id, $data)
    {
        try {
            $brandDetails = self::findBrandById($id);
            $brandDetails->update($data);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteBrands($id)
    {
        try {
            $brandDetails = self::findBrandById($id);
            $brandDetails->delete();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
