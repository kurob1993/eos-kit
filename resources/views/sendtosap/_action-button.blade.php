<form action="{{ route('sendtosap.destroy',$id) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <button class="btn btn-danger btn-xs m-2">
        <span class="fa fa-times"></span>
        Failed
    </button>
</form>

<form action="{{ route('sendtosap.update',$id) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <button class="btn btn-primary btn-xs m-2">
        <span class="fa fa-recycle"></span>
        Proses Ulang
    </button>
</form>