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
                switch ($overtime->stage->id) {
                    case Stage::waitingApprovalStage()->id: $class = 'info'; break; 
                    case Stage::sentToSapStage()->id: $class = 'warning'; break;
                    case Stage::successStage()->id: $class = 'primary';  break;
                    case Stage::failedStage()->id: $class = 'danger'; break;
                    case Stage::deniedStage()->id: $class = 'default';  break;
                }
                
                return '<span class="label label-' . $class . '">' 
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
                        'employee_name' => $approval->user->name
                        ]) . '<br />';
                return $a;
            })
            ->addColumn('action', function(AttendanceQuota $overtime){
                if ($overtime->is_finished) {

                } else if ($overtime->is_denied) {

                } else if ($overtime->is_sent_to_sap) {
                    // apakah stage-nya: sent to sap kemudian coba kirim manual
                    // atau dikirim secara otomatis (belum diakomodasi)
                    return view('all_overtimes._action', [
                        'model' => $overtime,
                        'integrate_url' => route('personnel_service.integrate', ['id' => $overtime->id, 'approval' => '']),
                        'confirm_url' => route('personnel_service.confirm', ['id' => $overtime->id, 'approval' => ''])
                    ]);
                } else if ($overtime->isFailed) {
                    // apakah stage-nya: failed
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
                'attendanceQuotaType', 
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
                'title' => 'Mulai',
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
                'data' => 'attendance_quota_type.text', 
                'name' => 'attendance_quota_type.text', 
                'title' => 'Jenis Cuti', 
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
