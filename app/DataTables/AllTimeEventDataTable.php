<?php

namespace App\DataTables;

use App\Models\TimeEvent;
use App\Models\Stage;
use Yajra\DataTables\Services\DataTable;

class AllTimeEventDataTable extends DataTable
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
            ->editColumn('id', function (TimeEvent $timeEvent){
                return $timeEvent->plain_id;
            })
            ->editColumn('stage.description', function (TimeEvent $timeEvent) {
                return '<span class="label label-' . $timeEvent->stage->class_description . '">' 
                . $timeEvent->stage->description . '</span>';
            })
            ->editColumn('check_date', function (TimeEvent $timeEvent) {
                return $timeEvent->formatted_check_date;
            })
            ->editColumn('time_event_approvals', function (TimeEvent $timeEvent){
                $approvals = $timeEvent->timeEventApprovals;
                $a = '';
                foreach ($approvals as $approval)
                    $a = $a . view('layouts._personnel-no-with-name', [
                        'personnel_no' => $approval->regno,
                        'employee_name' => $approval->employee->name
                        ]) . '<br />';
                return $a;
            })
            ->addColumn('action', function(TimeEvent $timeEvent){
                if ($timeEvent->is_finished) {

                } else if ($timeEvent->is_denied) {

                } else if ($timeEvent->is_sent_to_sap) {
                    // apakah stage-nya: sent to sap kemudian coba kirim manual
                    // atau dikirim secara otomatis (belum diakomodasi)
                    return view('components._action-confirm-integrate', [
                        'model' => $timeEvent,
                        'integrate_url' => route('personnel_service.integrate', ['id' => $timeEvent->id, 'approval' => 'time_event']),
                        'confirm_url' => route('personnel_service.confirm', ['id' => $timeEvent->id, 'approval' => 'time_event']),
                        'delete_url' => route('personnel_service.delete', ['id' => $timeEvent->id, 'approval' => 'time_event' ] )
                    ]);
                } else if ($timeEvent->isFailed) {
                    // apakah stage-nya: failed
                }  else if ($timeEvent->is_waiting_approval) {
                    // apakah stage-nya: waiting approval
                    return view('components._action-delete', [
                        'model' => $timeEvent,
                        'delete_url' => route('personnel_service.delete', [
                            'id' => $timeEvent->id, 'approval' => 'time_event' ] )
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
     * @param \App\Models\TimeEvent $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TimeEvent $model)
    {
        // ambil semua data cuti user
        return $model->newQuery()
            ->with([
                'timeEventType', 
                'stage',
                'user:personnel_no,name',
                'timeEventApprovals'
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
                'data' => 'check_date', 
                'name' => 'check_date', 
                'title' => 'Tanggal',
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'check_time', 
                'name' => 'check_time', 
                'title' => 'Pukul',
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'time_event_type.description', 
                'name' => 'time_event_type.description', 
                'title' => 'Jenis Slash', 
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'time_event_approvals', 
                'name' => 'time_event_approvals', 
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
        return 'AllTimeEvents_' . date('YmdHis');
    }
}
