@extends('layouts.app')

@section('content')
<!-- begin profile-container -->
<div class="profile-container">
    <!-- begin profile-section -->
    <div class="profile-section">
        <!-- begin profile-info -->
        <div class="profile-info">
            <!-- begin table -->
            <div class="table-responsive">
                <table class="table table-profile">
                    <thead>
                        <tr>
                            <th class="text-right">
                                <img src="{{ $picture }}" alt="" width="100px">
                            </th>
                            <th>
                                <h4>{{ $personalData->CNAME }} <small>{{ $position->HRP1000_S_STEXT }}</small>
                                </h4>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="highlight">
                            <td class="field">Pribadi</td>
                            <td></td>
                        </tr>
                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="field">TTL</td>
                            <td>{{ $personalData->GBORT }}, {{ $personalData->GBDAT }}</td>
                        </tr>
                        <tr>
                            <td class="field">Status Nikah</td>
                            <td>{{ $personalData->T502T_FATXT }}, {{ $personalData->FAMDT }}</td>
                        </tr>
                        <tr>
                            <td class="field">Agama</td>
                            <td>{{ $personalData->T516T_KNFTX }}</td>
                        </tr>
                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        <tr class="highlight">
                            <td class="field">Alamat</td>
                            <td></td>
                        </tr>
                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>

                        @foreach ($addresses['data'] as $address)
                        <tr>
                            <td class="field">{{ $address->T591S_STEXT }}</td>
                            <td>
                                {{ $address->STRAS }},
                                {{ $address->LOCAT }},
                                {{ $address->ORT01 }},
                                {{ $address->PSTLZ }}
                                <br><i class="fa fa-phone"></i>&nbsp;{{ $address->TELNR }}
                            </td>
                        </tr>
                        @endforeach

                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        <tr class="highlight">
                            <td class="field">Keluarga</td>
                            <td></td>
                        </tr>
                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        @foreach ($families['data'] as $family)
                        <tr>
                            <td class="field">{{ $family->T591S_STEXT }}</td>
                            <td>
                                @if ($family->FASEX == 1) <i class="fa fa-male"></i> @endif
                                @if ($family->FASEX == 2) <i class="fa fa-female"></i> @endif
                                &nbsp;{{ $family->FCNAM }}<br />
                                {{ $family->FGBOT }}, {{ $family->FGBDT }}<br />
                                @if ($family->KDZUG == 'Y')
                                Medical <i class="fa fa-check"></i>
                                @else
                                Medical <i class="fa fa-times"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach

                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        <tr class="highlight">
                            <td class="field">Pendidikan</td>
                            <td></td>
                        </tr>
                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        @foreach ($educations['data'] as $education)
                        <tr>
                            <td class="field">{{ $education->T517T_STEXT }}</td>
                            <td>
                                {{ $education->T517X_FTEXT }} -
                                {{ $education->INSTI }}
                                <br />
                                {{ $education->BEGDA }} -
                                {{ $education->ENDDA }}
                            </td>
                        </tr>
                        @endforeach

                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        <tr class="highlight">
                            <td class="field">Pelatihan</td>
                            <td></td>
                        </tr>
                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        @foreach ($trainings['data'] as $training)
                        <tr>
                            <td class="field">{{ $training->BEGDA }} - {{ $training->ENDDA }}</td class="field">
                            <td> {{ $training->TRAIN }} </td>
                        </tr>
                        @endforeach

                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        <tr class="highlight">
                            <td class="field">Aktivitas Internal</td>
                            <td></td>
                        </tr>
                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        @foreach ($internalActivities['data'] as $internalActivity)
                        <tr>
                            <td class="field">{{ $internalActivity->BEGDA }} - {{ $internalActivity->ENDDA }}</td
                                class="field">
                            <td><b>{{ $internalActivity->T591S_STEXT }}</b>
                                <br />
                                {{ $internalActivity->PTEXT_LINE1 }}
                                {{ $internalActivity->PTEXT_LINE2 }}
                            </td>
                        </tr>
                        @endforeach
                        @foreach ($intActivities['data'] as $intActivities)
                        <tr>
                            <td class="field">
                                {{ $intActivities->start_date->format('d/m/y') }} -
                                {{ $intActivities->end_date->format('d/m/y') }}
                            </td>
                            <td>
                                <b>{{ $intActivities->posisi }}</b>
                                <br>
                                {{ $intActivities->jenis_kegiatan }}<br>
                                {{ $intActivities->keterangan }}
                            </td>
                        </tr>
                        @endforeach

                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        <tr class="highlight">
                            <td class="field">Aktivitas Eksternal</td>
                            <td></td>
                        </tr>
                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        @foreach ($externalActivities['data'] as $externalActivity)
                        <tr>
                            <td>{{ $externalActivity->BEGDA }} - {{ $externalActivity->ENDDA }}</td>
                            <td><b>{{ $externalActivity->ZZPOSISI }}</b>
                                <br />
                                {{ $externalActivity->ORGNM }}
                                ({{ $externalActivity->T591S_STEXT }})
                            </td>
                        </tr>
                        @endforeach
                        @foreach ($extActivities['data'] as $extActivities)
                        <tr>
                            <td class="field">
                                {{ $extActivities->start_date->format('d/m/y') }} -
                                {{ $extActivities->end_date->format('d/m/y') }}
                            </td>
                            <td>
                                <b>{{ $extActivities->posisi }}</b>
                                <br>
                                {{ $extActivities->jenis_kegiatan }}<br>
                                {{ $extActivities->keterangan }}
                            </td>
                        </tr>
                        @endforeach
                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        <tr class="highlight">
                            <td class="field">Aktivitas Lainnya</td>
                            <td></td>
                        </tr>
                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
                        @foreach ($others['data'] as $other)
                        <tr>
                            <td class="field">{{ $other->BEGDA }} - {{ $other->ENDDA }}</td>
                            <td><b>{{ $other->T591S_STEXT }}</b>
                                <br />
                                {{ $other->PTEXT_LINE1 }}
                                {{ $other->PTEXT_LINE2 }}
                            </td>
                        </tr>
                        @endforeach
                        @foreach ($otherActivities['data'] as $otherActivities)
                        <tr>
                            <td class="field">
                                {{ $otherActivities->start_date->format('d/m/y') }} -
                                {{ $otherActivities->end_date->format('d/m/y') }}
                            </td>
                            <td>
                                <b>{{ $otherActivities->jenis_kegiatan }}</b>
                                <br>
                                {{ $otherActivities->keterangan }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- end table -->
        </div>
        <!-- end profile-info -->
    </div>
    <!-- end profile-section -->

</div>
<!-- end profile-container -->
@endsection

@push('styles')
@endpush

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    window.print();
</script>
@endpush

@push('on-ready-scripts')
@endpush