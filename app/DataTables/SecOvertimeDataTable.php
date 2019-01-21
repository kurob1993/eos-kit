<?php

namespace App\DataTables;

use App\Models\AttendanceQuota as Overtime;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class SecOvertimeDataTable extends DataTable
{

    public function dataTable($query)
    {
        $request = $this->request();

        return datatables($query)
            ->editColumn('id', function (Overtime $overtime){
                return $overtime->plain_id;
            })
            ->editColumn('stage.description', function (Overtime $overtime) {
                return '<span class="label label-' .$overtime->stage->class_description . '">' 
                . $overtime->stage->description . '</span>';
            })
            ->editColumn('start_date', function (Overtime $overtime) {
                return $overtime->start_date;
            })
            ->editColumn('end_date', function (Overtime $overtime) {
                return $overtime->end_date;
            })
            ->editColumn('attendance_quota_approval', function (Overtime $overtime){
                $approvals = $overtime->attendanceQuotaApproval;
                $a = '';
                foreach ($approvals as $approval)
                    $a = $a . view('layouts._personnel-no-with-name', [
                        'personnel_no' => $approval->regno,
                        'employee_name' => $approval->employee->name
                        ]) . '<br />';
                return $a;
            })
            ->addColumn('duration', function(Overtime $overtime){
                return $overtime->duration . ' menit';
            })            
            ->escapeColumns([])
            ->filter(function ($query) use ($request) {
                if ($request->has('stage_id')) {
                    switch ($request->input('stage_id')) {
                        case Stage::sentToSapStage()->id: $query->sentToSapOnly(); break;
                        case Stage::waitingApprovalStage()->id: $query->waitingApprovalOnly(); break;
                        case Stage::successStage()->id: $query->successOnly(); break;
                        case Stage::failedStage()->id: $query->failedOnly(); break;
                        case Stage::deniedStage()->id: $query->deniedOnly(); break;
                    }
                } 
            }, true);
    }

    public function query(Overtime $model)
    {
        // ambil semua data cuti user
        return $model->newQuery()
            ->where('secretary_id', Auth::guard('secr')->user()->id)
            ->with([
                'overtimeReason', 
                'stage',
                'employee:personnel_no,name',
                'attendanceQuotaApproval'
                ]
            );
    }

    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->addAction(['width' => '80px'])
                    ->parameters($this->getBuilderParameters());
    }

    public function getBuilderParameters()
    {
        return [
            'dom' => 'frtip',
            'pageLength' => 50,
            'responsive' => true,
            "language" => [
                'processing' => '<i class="fa fa-spinner fa-spin fa-fw"></i><span class="sr-only">Loading...</span> '
            ],
        ];
    }

    protected function getColumns()
    {
        return [
            [ 
                'data' => 'id', 
                'name' => 'id', 
                'title' => 'ID',
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'personnel_no', 
                'name' => 'personnel_no', 
                'title' => 'NIK',
                'orderable' => false,
            ],
            [ 
                'data' => 'employee.name', 
                'name' => 'employee.name', 
                'title' => 'Nama',
                'orderable' => false,
            ],
            [ 
                'data' => 'start_date', 
                'name' => 'start_date', 
                'title' => 'Tanggal Mulai',
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'from', 
                'name' => 'from', 
                'title' => 'Jam Mulai',
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'end_date', 
                'name' => 'end_date', 
                'title' => 'Berakhir',
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'to', 
                'name' => 'to', 
                'title' => 'Jam Berakhir',
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'duration', 
                'name' => 'duration', 
                'title' => 'Durasi', 
                'class' => 'text-center',
                'searchable' => false,
                'orderable' => false,
            ],            
            [ 
                'data' => 'overtime_reason.text', 
                'name' => 'overtime_reason.text', 
                'title' => 'Alasan lembur', 
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'attendance_quota_approval', 
                'name' => 'attendance_quota_approval', 
                'title' => 'Approval', 
                'searchable' => false, 
                'class' => 'none',
                'orderable' => false,
            ],
            [ 
                'data' => 'stage.description', 
                'name' => 'stage.description', 
                'title' => 'Tahap', 
                'searchable' => false, 
                'orderable' => false,
            ],
        ];
    }

    protected function filename()
    {
        return 'SecOvertime_' . date('YmdHis');
    }
}
