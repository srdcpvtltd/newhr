<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\Procurement;

class ProcurementRepository
{

    public function getProcruementNumber()
    {
        $p_id = Procurement::latest('id')->first();
        $company_name = Company::first()->name;
        if ($p_id) {
            return $company_name . date('Y') . $p_id + 1;
        } else {
            return $company_name . date('Y') . 1;
        }
    }

    public function store($data) {
        return Procurement::create($data)->fresh();
    }
}
