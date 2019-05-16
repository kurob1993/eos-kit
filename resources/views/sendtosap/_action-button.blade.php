<form action="{{ route('sendtosap.update',$id) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <button class="btn btn-danger btn-xs">
        <span class="fa fa-times"></span>
        Failed
    </button>
</form>