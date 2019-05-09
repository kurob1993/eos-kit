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
                            <th></th>
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
                            <td class="field">Keluarga</td>
                            <td></td>
                        </tr>
                        <tr class="divider">
                            <td colspan="2"></td>
                        </tr>
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
@endpush

@push('on-ready-scripts')
@endpush