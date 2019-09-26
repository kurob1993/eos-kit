@if($activity->stage_id == 1)
<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-xs btn-success" title="Approve" data-toggle="modal" data-target="#approve{{$activity->id}}"
    data-backdrop="static">
    <i class="fa fa-check" aria-hidden="true"></i>
</button>
<button type="button" class="btn btn-xs btn-danger" title="Denied" data-toggle="modal" data-target="#denied{{$activity->id}}"
    data-backdrop="static">
    <i class="fa fa-times" aria-hidden="true"></i>
</button>

<!-- Modal -->
<div id="approve{{$activity->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('personnel_service.internal-activity.update',$activity->id) }}" method="post"
                style="display: inline">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input type="hidden" value="2" name="stage_id">

                <div class="modal-body">
                    <p>Yakin Menyetujui Data ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" title="Approve">
                        YA
                    </button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Tidak</button>
                </div>

            </form>
        </div>
    </div>
</div>

<div id="denied{{$activity->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('personnel_service.internal-activity.update',$activity->id) }}" method="post"
                style="display: inline">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <input type="hidden" value="5" name="stage_id">

                <div class="modal-body">
                    <p>Yakin Menolak Data ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" title="Denied">
                        YA
                    </button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Tidak</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endif
@if($activity->stage_id == 2)
<span class="label label-success">{{ $activity->stage->description }}</span>
@endif
@if($activity->stage_id == 5)
<span class="label label-danger">{{ $activity->stage->description }}</span>
@endif