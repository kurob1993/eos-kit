<?php

namespace App\DataTables;

Use Storage;
use Yajra\DataTables\Services\DataTable;
use App\Models\Attendance;
use App\Models\Stage;

class AllAttendancePermitDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $request = $this->request();
        
        return datatables($query)
            ->editColumn('id', function (Attendance $attendance){
                return $attendance->plain_id;
            })
            ->editColumn('stage.description', function (Attendance $attendance) {
                return '<span class="label label-' . $attendance->stage->class_description  . '">' 
                . $attendance->stage->description . '</span>';
            })
            ->editColumn('start_date', function (Attendance $attendance) {
                return $attendance->formatted_start_date;
            })
            ->editColumn('end_date', function (Attendance $attendance) {
                return $attendance->formatted_end_date;
            })
            ->editColumn('attendance_approvals', function (Attendance $attendance){
                $approvals = $attendance->attendanceApprovals;
                $a = '';
                foreach ($approvals as $approval)
                    $a = $a . view('layouts._personnel-no-with-name', [
                        'personnel_no' => $approval->regno,
                        'employee_name' => $approval->employee['name']
                        ]) . '<br />';
                return $a;
            })
            ->editColumn('attachment', function (Attendance $attendance){
                return '<img class="center-block img-responsive" src="' 
                    . Storage::url($attendance->attachment) . '">';
            })
            ->addColumn('duration', function(Attendance $attendance){
                return $attendance->duration . ' hari';
            })            
            ->addColumn('action', function(Attendance $attendance){
                if ($attendance->is_finished) {

                } else if ($attendance->is_denied) {

                } else if ($attendance->is_sent_to_sap) {
                    // apakah stage-nya: sent to sap kemudian coba kirim manual
                    // atau dikirim secara otomatis (belum diakomodasi)
                    return view('components._action-confirm-integrate', [
                        'model' => $attendance,
                        'integrate_url' => route('personnel_service.integrate', ['id' => $attendance->id, 'approval' => 'attendance']),
                        'confirm_url' => route('personnel_service.confirm', ['id' => $attendance->id, 'approval' => 'attendance']),
                        'delete_url' => route('personnel_service.delete', ['id' => $attendance->id, 'approval' => 'attendance' ] )
                    ]);
                } else if ($attendance->isFailed) {
                    // apakah stage-nya: failed
                } else if ($attendance->is_waiting_approval) {
                    // apakah stage-nya: waiting approval
                    return view('components._action-delete', [
                        'model' => $attendance,
                        'delete_url' => route('personnel_service.delete', [
                            'id' => $attendance->id, 'approval' => 'attendance' ] )
                    ]);
                }               
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
                        case Stage::cancelledStage()->id: $query->cancelledOnly(); break;
                    }
                } 
                if ($request->has('month_id') && $request->has('year_id')) {
                    $query->monthYearOf($request->input('month_id'),$request->input('year_id'))->get();
                }
            }, true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Attendance $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Attendance $model)
    {
        // ambil semua data cuti user
        return $model->newQuery()
            ->with([
                'attendanceType', 
                'stage',
                'user:personnel_no,name',
                'attendanceApprovals'
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
                    ->minifiedAjax('', 
                        'data.stage_id = $("#select-filter option:selected").val();
                        data.month_id = $("#month-filter option:selected").val();
                        data.year_id = $("#year-filter option:selected").val();', 
                        [ ])
                    ->addAction(['width' => '80px'])
                    ->parameters($this->getBuilderParameters());
    }

    public function getBuilderParameters()
    {
        return [
            'dom' =>    "<'row'<'col-sm-1 pull-left'l> <'col-sm-2'<'monthperiod'>> <'col-sm-2'<'yearperiod'>> <'col-sm-2'<'toolbar'>> <'col-sm-1'B> <'col-sm-3 pull-right'f>>" .
                        "<'row'<'col-sm-12'tr>>" .
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            'pageLength' => config('emss.personnel_service.page_length'),
            'buttons' => ['excel'],
            'responsive' => true,
            "language" => [
                'processing' => '<i class="fa fa-spinner fa-spin fa-fw"></i><span class="sr-only">Loading...</span> ',
                'lengthMenu' => '_MENU_',
                'search' => '<i class="fa fa-search"></i>',
            ],
            // 'columnDefs' => [ [ 'responsivePriority' => 1, 'targets' => 7 ], ],
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
                'data' => 'note', 
                'name' => 'note', 
                'title' => 'Catatan', 
                'class' => 'none',
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
                'data' => 'attendance_type.text', 
                'name' => 'attendance_type.text', 
                'title' => 'Jenis Izin', 
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'attendance_approvals', 
                'name' => 'attendance_approvals', 
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
            [ 
                'data' => 'attachment', 
                'name' => 'attachment', 
                'title' => 'Lampiran', 
                'class' => 'none',
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
        return 'AllAttendancePermits_' . date('YmdHis');
    }
}
