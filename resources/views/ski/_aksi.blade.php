<a class="btn btn-xs btn-warning m-b-2" data-toggle="modal" data-target="#modal-dialog"
    onclick="
        $('.modal-body').load('{{route('ski.show',['ski' => $id ])}}',function(){
            $('#modal-dialog').modal({show:true});
        });
    "
>
    <i class="fa fa-list-alt" aria-hidden="true"></i>
    Detail 
</a>

@foreach ($ski_approval as $item)
    @if (Auth::user()->personnel_no == $item['regno'])
    <a class="btn btn-xs btn-primary m-b-2" data-toggle="modal" data-target="#modal-dialog"
        onclick="
            $('.modal-body').load('{{route('ski.penilaian',['ski' => $id ])}}',function(){
                $('#modal-dialog').modal({show:true});
            });
        "
    >
        <i class="fa fa-pencil-square" aria-hidden="true"></i>
        Penilaian
    </a>
    @endif
@endforeach
