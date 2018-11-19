<?php

namespace App\DataTables;

use App\Models\AttendanceQuota;
use App\Models\Stage;
use Yajra\DataTables\Services\DataTable;

class AllOvertimeDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->editColumn('id', function (AttendanceQuota $overtime){
                return $overtime->plain_id;
            })
            ->editColumn('stage.description', function (AttendanceQuota $overtime) {
                return '<span class="label label-' .$overtime->stage->class_description . '">' 
                . $overtime->stage->description . '</span>';
            })
            ->editColumn('start_date', function (AttendanceQuota $overtime) {
                return $overtime->formatted_start_date;
            })
            ->editColumn('end_date', function (AttendanceQuota $overtime) {
                return $overtime->formatted_end_date;
            })
            ->editColumn('attendance_quota_approval', function (AttendanceQuota $overtime){
                $approvals = $overtime->attendanceQuotaApproval;
                $a = '';
                foreach ($approvals as $approval)
                    $a = $a . view('layouts._personnel-no-with-name', [
                        'personnel_no' => $approval->regno,
                        'employee_name' => $approval->employee->name
                        ]) . '<br />';
                return $a;
            })
            ->addColumn('duration', function(AttendanceQuota $overtime){
                return $overtime->duration . ' menit';
            })            
            ->addColumn('action', function(AttendanceQuota $overtime){
                if ($overtime->is_finished) {
                
                } else if ($overtime->is_denied) {

                } else if ($overtime->is_sent_to_sap) {
                    // apakah stage-nya: sent to sap kemudian coba kirim manual
                    // atau dikirim secara otomatis (belum diakomodasi)
                    return view('components._action-confirm-integrate', [
                        'model' => $overtime,
                        'integrate_url' => route('personnel_service.integrate', ['id' => $overtime->id, 'approval' => 'overtime']),
                        'confirm_url' => route('personnel_service.confirm', ['id' => $overtime->id, 'approval' => 'overtime'])
                    ]);
                } else if ($overtime->isFailed) {
                    // apakah stage-nya: failed
                }  else if ($overtime->is_waiting_approval) {
                    // apakah stage-nya: waiting approval
                    return view('components._action-delete', [
                        'model' => $overtime,
                        'delete_url' => route('personnel_service.delete', [
                            'id' => $overtime->id, 'approval' => 'overtime' ] )
                    ]);
                } 
            })
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AttendanceQuota $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AttendanceQuota $model)
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

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
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
            'dom' => 'Bfrtip',
            'pageLength' => 50,
            'buttons' => ['excel'],
            'responsive' => true,
            "language" => [
                'processing' => '<i class="fa fa-spinner fa-spin fa-fw"></i><span class="sr-only">Loading...</span> '
            ],
            // 'buttons' => [ 'extend' => 'excel', 'exportOptions' => [ 'columns' => [ 'id', ] ] ], 'paging' => false, 'searching' => false, 'responsive' => [ 'details' => 'false' ], 
        ];
    }

    /**
     * Get columns.
     *
     * @return array
     */
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

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'AllOvertimes_' . date('YmdHis');
    }
}
