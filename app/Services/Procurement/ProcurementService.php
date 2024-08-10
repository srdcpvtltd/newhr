<?php

namespace App\Services\Procurement;

use App\Repositories\ProcurementRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProcurementService
{

    public function __construct(
        private ProcurementRepository $procurementRepo,
    ) {}

    public function storeProcurement($data)
    {
        try {
            DB::beginTransaction();
            $procurement_detail = $this->procurementRepo->store($data);
            DB::commit();
            return $procurement_detail;
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }
}
