
<form method="POST" action="{{ route('preferdis.periode.delete', $id) }}" class="form-inline">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <button class="btn btn-danger btn-xs" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')" type="submit"><i class="fa fa-close"></i></button>
</form>
