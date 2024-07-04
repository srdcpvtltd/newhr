<?php

namespace App\Exports;

use App\Helpers\AppHelper;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceDayWiseExport implements FromView, ShouldAutoSize
{
    protected $attendanceDayWiseRecord;
    protected $filterParameter;

    function __construct($attendanceDayWiseRecord,$filterParameter)
    {
        $this->attendanceDayWiseRecord = $attendanceDayWiseRecord;
        $this->filterParameter = $filterParameter;
    }

    public function view(): View
    {
        $appTimeSetting = AppHelper::check24HoursTimeAppSetting();

        return view('admin.attendance.export.attendance-day-wise-export', [
            'attendanceDayWiseRecord' => $this->attendanceDayWiseRecord,
            'dayDetail' => $this->filterParameter,
            'appTimeSetting'=>$appTimeSetting,
        ]);
    }

}

