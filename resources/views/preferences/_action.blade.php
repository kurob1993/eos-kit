<form method="POST" action="{{ route('preference.destroy',$id) }}">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-close"></i></button>
</form>
