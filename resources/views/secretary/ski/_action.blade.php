<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-dialog{{$ski->id}}">
    <i class="fa fa-list-ul" aria-hidden="true"></i>
    Detail
</button>

<div class="modal fade" id="modal-dialog{{$ski->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Sasaran Kinerja Individu (ID: <span id="title-span">{{$ski->plain_id}}</span>)</h4>
            </div>
            <div class="modal-body text-left">
                <table id="table-detail" class="table table-bordered table-condensed m-b-0"
                    data-id="{{ $ski->plain_id }}">
                    <tbody>
                        <tr>
                            <td>Nama</td>
                            <td> {{ $ski->employee->PersonnelNoWithName }}</td>
                        </tr>
                        <tr>
                            <td>Periode</td>
                            <td>{{$ski->month}}-{{$ski->year}}</td>
                        </tr>
                        <tr>
                            <td>Atasan</td>
                            <td>
                                @foreach ($ski->skiApproval as $item)
                                @component('layouts._personnel-no-with-name', [
                                'personnel_no' => $item->employee->personnel_no,
                                'employee_name' => $item->employee->name])
                                @endcomponent
                                <br />
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Tahap</td>
                            <td><span class="label label-default">{{ $ski->stage->description }}</span></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-condensed m-t-15" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">NO</th>
                            <th class="text-center" style="width: 10%">KLP</th>
                            <th class="text-center" style="width: 30%">Sasaran Kerja</th>
                            <th class="text-center" style="width: 10%">Kode</th>
                            <th class="text-center" style="width: 25%">Ukuran Prestasi Kerja</th>
                            <th class="text-center" style="width: 6%">Bobot</th>
                            <th class="text-center" style="width: 6%">Skor</th>
                            <th class="text-center" style="width: 8%">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ski->skiDetail as $key => $item)
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            <td>{{$item->klp}}</td>
                            <td>{{$item->sasaran}}</td>
                            <td>{{$item->kode}}</td>
                            <td>{{$item->ukuran}}</td>
                            <td>{{$item->bobot}}</td>
                            <td>{{$item->skor}}</td>
                            <td>{{$item->nilai}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (isset($edit))
                    @if (!$ski->skiApproval->where('regno',Auth::user()->personnel_no)->first()->IsApproved)
                        <a class="btn btn-warning" href="{{route('ski.edit',$ski->id)}}">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            Ubah
                        </a>
                    @endif
                @endif
                <button class="btn btn-default" type="button" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>