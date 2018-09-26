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
        return datatables($query)
            ->editColumn('id', function (TimeEvent $timeEvent){
                return $timeEvent->plain_id;
            })
            ->editColumn('stage.description', function (TimeEvent $timeEvent) {
                switch ($timeEvent->stage->id) {
                    case Stage::waitingApprovalStage()->id: $class = 'info'; break; 
                    case Stage::sentToSapStage()->id: $class = 'warning'; break;
                    case Stage::successStage()->id: $class = 'primary';  break;
                    case Stage::failedStage()->id: $class = 'danger'; break;
                    case Stage::deniedStage()->id: $class = 'default';  break;
                }
                
                return '<span class="label label-' . $class . '">' 
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
                        'employee_name' => $approval->user->name
                        ]) . '<br />';
                return $a;
            })
            ->addColumn('action', function(TimeEvent $timeEvent){
                if ($timeEvent->is_finished) {

                } else if ($timeEvent->is_denied) {

                } else if ($timeEvent->is_sent_to_sap) {
                    // apakah stage-nya: sent to sap kemudian coba kirim manual
                    // atau dikirim secara otomatis (belum diakomodasi)
                    return view('all_time_events._action', [
                        'model' => $timeEvent,
                        'integrate_url' => route('personnel_service.integrate', ['id' => $timeEvent->id, 'approval' => '']),
                        'confirm_url' => route('personnel_service.confirm', ['id' => $timeEvent->id, 'approval' => ''])
                    ]);
                } else if ($timeEvent->isFailed) {
                    // apakah stage-nya: failed
                }                
            })
            ->escapeColumns([]);
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