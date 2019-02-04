<?php

namespace App\DataTables;

use App\Models\AbsenceQuota;
use Yajra\DataTables\Services\DataTable;

class AllAbsenceQuotaDataTable extends DataTable
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
            ->editColumn('id', function (AbsenceQuota $absenceQuota){
                return $absenceQuota->plain_id;
            })
            ->editColumn('start_date', function (AbsenceQuota $absenceQuota) {
                return $absenceQuota->formatted_start_date;
            })
            ->editColumn('end_date', function (AbsenceQuota $absenceQuota) {
                return $absenceQuota->formatted_end_date;
            })
            ->editColumn('start_deduction', function (AbsenceQuota $absenceQuota) {
                return $absenceQuota->formatted_start_deduction;
            })
            ->editColumn('end_deduction', function (AbsenceQuota $absenceQuota) {
                return $absenceQuota->formatted_end_deduction;
            })
            ->editColumn('user.name', function (AbsenceQuota $absenceQuota) {
                $nama = $absenceQuota->user()->first();
                return $nama['name'];
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AbsenceQuota $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AbsenceQuota $model)
    {
        // ambil semua data cuti user
        return $model->newQuery()
              ->with([
                'absenceType', 
                'user:personnel_no,name',
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
                'data' => 'absence_type.text', 
                'name' => 'absence_type.text', 
                'title' => 'Jenis Cuti', 
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'start_deduction', 
                'name' => 'start_deduction', 
                'title' => 'Start Deduction',
                'searchable' => false,
                'orderable' => false,
                'class' => 'none',
            ],
            [ 
                'data' => 'end_deduction', 
                'name' => 'end_deduction', 
                'title' => 'End Deduction',
                'searchable' => false,
                'orderable' => false,
                'class' => 'none',
            ],
            [ 
                'data' => 'number', 
                'name' => 'number', 
                'title' => 'Kuota Cuti',
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'deduction', 
                'name' => 'deduction', 
                'title' => 'Terpakai',
                'searchable' => false,
                'orderable' => false,
            ],
        ];
    }
}