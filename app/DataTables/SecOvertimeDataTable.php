<?php

namespace App\DataTables;

use App\Models\AttendanceQuota as Overtime;
use Yajra\DataTables\Services\DataTable;

class SecOvertimeDataTable extends DataTable
{

    protected $actions = ['print', 'excel', 'myCustomAction'];

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('action', 'secovertime.action');
    }

    public function query(Overtime $model)
    {
        // ambil semua data cuti user
        return $model->newQuery()
            ->with([
                'overtimeReason', 
                'stage',
                'user:personnel_no,name',
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

    public function myCustomAction()
    {
        return 'hello add action';
    }

    public function getBuilderParameters()
    {
        return [
            'dom' => 'Bfrtip',
            'pageLength' => 50,
            'buttons'      => ['print', 'excel', 'myCustomAction'],
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
                'data' => 'user.name', 
                'name' => 'user.name', 
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
