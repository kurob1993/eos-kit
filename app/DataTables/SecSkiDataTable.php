<?php

namespace App\DataTables;

use App\Models\Ski as Ski;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class SecSkiDataTable extends DataTable
{

    public function dataTable($query)
    {
        $request = $this->request();

        return datatables($query)
            ->editColumn('id', function (Ski $Ski){
                return $Ski->plain_id;
            })
            ->editColumn('stage.description', function (Ski $Ski) {
                return '<span class="label label-' .$Ski->stage->class_description . '">' 
                . $Ski->stage->description . '</span>';
            })
            ->editColumn('secretary.name', function (Ski $Ski) {
                if($Ski->secretary){
                    return $Ski->secretary->name.
                        ' ('.$Ski->secretary->email.')';
                }
                return '<span class="label label-default">'.
                            $Ski->userDirnik->personnel_no
                        .'</span> '.
                        $Ski->userDirnik->name;
            })
            ->editColumn('month', function (Ski $Ski) {
                return '<span class="label label-warning">'.
                            $Ski->month."/".$Ski->year
                        .'</span> ';
            })
            ->editColumn('ski_approval', function (Ski $Ski){
                $approvals = $Ski->skiApproval;
                $a = '';
                foreach ($approvals as $approval)
                    $a = $a . view('layouts._personnel-no-with-name', [
                        'personnel_no' => $approval->regno,
                        'employee_name' => $approval->employee['name']
                        ]) . '<br />';
                return $a;
            })         
            ->editColumn('action', function (Ski $Ski){
                return view('secretary.ski._action',['ski'=>$Ski] );
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
            }, true);
    }

    public function query(Ski $model)
    {
        // ambil semua data cuti user
        return $model->newQuery()
            ->where('secretary_id', Auth::guard('secr')->user()->id)
            ->with([ 
                'stage',
                'employee:personnel_no,name',
                'skiApproval'
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
                'data' => 'month', 
                'name' => 'month', 
                'title' => 'Periode',
                'orderable' => false,
            ],
            [ 
                'data' => 'ski_approval', 
                'name' => 'ski_approval', 
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
                'data' => 'secretary.name', 
                'name' => 'secretary.name', 
                'title' => 'Dibuat oleh', 
                'searchable' => false, 
                'orderable' => false,
            ],
        ];
    }

    protected function filename()
    {
        return 'SecSki_' . date('YmdHis');
    }
}
