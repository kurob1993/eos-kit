<?php

namespace App\DataTables;

use App\Models\Absence;
use Yajra\DataTables\Services\DataTable;

class AllLeavesDataTable extends DataTable
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
            ->editColumn('stage.description', function (Absence $absence) {
                return '<span class="label label-default">' 
                . $absence->stage->description . '</span>';
            })
            ->editColumn('start_date', function (Absence $absence) {
                return $absence->start_date->format(config('emss.date_format'));
            })
            ->editColumn('end_date', function (Absence $absence) {
                return $absence->end_date->format(config('emss.date_format'));
            })
            ->addColumn('action', function(Absence $absence){
                // apakah stage-nya finished OR denied kemudian biarkanlah
                if ($absence->isFinished) {
                    return '<span class="label label-primary">Finished</span>';
                } else if ($absence->isDenied) {
                    return '<span class="label label-primary">Denied</span>';
                // apakah stage-nya: sent to sap kemudian coba kirim manual
                // atau dikirim secara otomatis (belum diakomodasi)
                } else if ($absence->isSentToSap) {
                    return view('all_leaves._action', [
                        'model' => $absence,
                        'integrate_url' => route('all_leaves.integrate', $absence->id),
                        'confirm_url' => route('all_leaves.confirm', $absence->id)
                    ]);
                // apakah stage-nya: failed
                } else if ($absence->isFailed) {
                    return '<span class="label label-primary">Failed</span>';
                }                
            })
            ->escapeColumns([3]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Absence $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Absence $model)
    {
        // ambil semua data cuti user
        return $model->newQuery()->leavesOnly()->with(['absenceType', 'stage']);
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
            'buttons' => ['excel'],
            // 'paging' => false, 
            // 'searching' => false, 
            // 'responsive' => [ 
            //     'details' => 'false' 
            //     ], 
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
            'id',
            'start_date',
            'end_date',
            'stage.description'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'AllLeaves_' . date('YmdHis');
    }
}
