<?php

namespace App\Repositories;

use App\Models\Brand;

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

    public function findBrandById($id){
        $brand_details = Brand::find($id)->first();

        return $brand_details;
    }

    public function updateBrandDetails($id,$data ){
        try {
           $brandDetails = self::findBrandById($id);
           $brandDetails->update($data);
        } catch (\Exception $e) {
           
        }
    }
}
