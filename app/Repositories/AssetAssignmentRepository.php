<?php

namespace App\Repositories;

use App\Models\AssetAssignment;

class AssetAssignmentRepository
{
    public function getAllAssetAssignments($select, $with)
    {
        return AssetAssignment::select($select)->with($with)->get()->map(function ($item) {
            // dd($item);
            $item->assign_to = $item->users->name;
            $item->asset_name = $item->assets->name;
            $item->asset_types = $item->asset_types->name;
            $item->assign_date = date('d-m-Y', strtotime($item->assign_date));
            $item->return_date = $item->return_date ? date('d-m-Y', strtotime($item->return_date)) : null;

            return $item;
        });
    }
}
