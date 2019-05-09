@extends('layouts.app')

@section('content')
<!-- begin #page-container -->
@component('layouts.employee._page-container', ['page_header' => 'Curriculum Vitae'])
<div class="row">
    <div class="col-lg-12 col-xl-9">
        @include('layouts._flash')
        <!-- begin of dashboard nav-tabs  -->
        <ul class="nav nav-tabs nav-tabs-primary nav-justified nav-justified-mobile">
            <li class="active">
                <a href="#tab-personal-data" data-toggle="tab" aria-expanded="true"> Pribadi
                    @if (false)
                    <span class="badge pull-right m-l-5">
                        <h5>Data Pribadi</h5>
                    </span>
                    @endif
                </a>
            </li>
            <li class="">
                <a href="#tab-educations" data-toggle="tab" aria-expanded="true"> Pendidikan
                    @if (false)
                    <span class="badge pull-right m-l-5">

                    </span>
                    @endif
                </a>
            </li>
            <li class="">
                <a href="#tab-activities" data-toggle="tab" aria-expanded="true"> Aktivitas
                    @if (false)
                    <span class="badge pull-right m-l-5">

                    </span>
                    @endif
                </a>
            </li>
            <li class="">
                <a href="#tab-download" data-toggle="tab" aria-expanded="true">
                    <i class="fa fa-download"></i>
                </a>
            </li>
        </ul>
        <!-- end of dashboard nav-tabs  -->

        <!-- begin of tab-content  -->
        <div class="tab-content">
            <!-- begin of personal-data tab  -->
            <div class="tab-pane fade active in" id="tab-personal-data">
                <div class="panel-body p-0">
                    <h3 class="m-t-10 m-b-0">Data Pribadi</h3>
                    <small>Terakhir diubah di SAP: {{ $personalData->AEDTM }}</small>
                    <div class="m-t-10 media media">
                        <div class="media-body">
                            <dl class="dl-horizontal">
                                <dt>Nama</dt>
                                <dd class="m-b-10">{{ $personalData->CNAME }}</dd>
                                <dt>Tempat, Tanggal Lahir</dt>
                                <dd class="m-b-10">{{ $personalData->GBORT }}, {{ $personalData->GBDAT }}</dd>
                                <dt>Status Nikah</dt>
                                <dd class="m-b-10">{{ $personalData->T502T_FATXT }} - {{ $personalData->FAMDT }}</dd>
                                <dt>Agama</dt>
                                <dd class="m-b-10">{{ $personalData->T516T_KNFTX }}</dd>
                            </dl>
                        </div>
                    </div>

                    <h3 class="m-t-10 m-b-0">Alamat</h3>
                    <small>Terakhir diubah di SAP: {{ $addresses['last_updated'] }}</small>
                    <div class="media media">
                        <div class="media-body">
                            <dl class="dl-horizontal">
                                @foreach ($addresses['data'] as $address)
                                <dt>{{ $address->T591S_STEXT }}</dt>
                                <dd>
                                    {{ $address->STRAS }},
                                    {{ $address->LOCAT }},
                                    {{ $address->ORT01 }},
                                    {{ $address->PSTLZ }}
                                </dd>
                                <dt></dt>
                                <dd class="m-b-10">
                                    <i class="fa fa-phone"></i>&nbsp;{{ $address->TELNR }}
                                </dd>
                                @endforeach
                            </dl>
                        </div>
                    </div>

                    <h3 class="m-t-10 m-b-0">Keluarga</h3>
                    <small>Terakhir diubah di SAP: {{ $families['last_updated'] }}</small>
                    <div class="media media">
                        <div class="media-body">
                            <dl class="dl-horizontal">
                                @foreach ($families['data'] as $family)
                                <dt>{{ $family->T591S_STEXT }}</dt>
                                <dd>
                                    @if ($family->FASEX == 1) <i class="fa fa-male"></i> @endif
                                    @if ($family->FASEX == 2) <i class="fa fa-female"></i> @endif
                                    &nbsp;{{ $family->FCNAM }}
                                </dd>
                                <dt></dt>
                                <dd>
                                    {{ $family->FGBOT }},
                                    {{ $family->FGBDT }}
                                </dd>
                                <dt></dt>
                                <dd class="m-b-10">
                                    @if ($family->KDZUG == 'Y')
                                    Medical <i class="fa fa-check"></i>
                                    @else
                                    Medical <i class="fa fa-times"></i>
                                    @endif
                                </dd>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of personal-data tab  -->

            <!-- begin of educations tab  -->
            <div class="tab-pane fade" id="tab-educations">
                <div class="panel-body p-0">
                    <h3 class="m-t-10 m-b-0">Pendidikan</h3>
                    <small>Terakhir diubah di SAP: {{ $educations['last_updated'] }}</small>
                    <div class="media media">
                        <div class="media-body">
                            <dl class="dl-horizontal">
                                @foreach ($educations['data'] as $education)
                                <dt>{{ $education->T517T_STEXT }}</dt>
                                <dd>
                                    {{ $education->T517X_FTEXT }} -
                                    {{ $education->INSTI }}
                                </dd>
                                <dt></dt>
                                <dd class="m-b-10">
                                    {{ $education->BEGDA }} -
                                    {{ $education->ENDDA }}
                                </dd>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                    <h3 class="m-t-10 m-b-0">Pelatihan</h3>
                    <small>Terakhir diubah di SAP: {{ $trainings['last_updated'] }}</small>
                    <div class="media media">
                            <div class="media-body">
                                <dl class="dl-horizontal">
                                    @foreach ($trainings['data'] as $training)
                                    <dt>{{ $training->BEGDA }} - {{ $training->ENDDA }}</dt>
                                    <dd class="m-b-10">
                                        {{ $training->TRAIN }}
                                    </dd>
                                    @endforeach
                                </dl>
                            </div>
                        </div>
                </div>
            </div>
            <!-- end of educations tab  -->

            <!-- begin of activities tab  -->
            <div class="tab-pane fade" id="tab-activities">
                <div class="panel-body p-0">
                    <h3 class="m-t-10 m-b-0">Aktivitas Internal</h3>
                    <small>Terakhir diubah di SAP: {{ $internalActivities['last_updated'] }}</small>
                    <div class="media media">
                        <div class="media-body">
                            <dl class="dl-horizontal">
                                @foreach ($internalActivities['data'] as $internalActivity)
                                <dt>{{ $internalActivity->BEGDA }} - {{ $internalActivity->ENDDA }}</dt>
                                <dd><b>{{ $internalActivity->T591S_STEXT }}</b></dd>
                                <dt></dt>
                                <dd class="m-b-10">
                                    {{ $internalActivity->PTEXT_LINE1 }}
                                    {{ $internalActivity->PTEXT_LINE2 }}
                                </dd>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                    <h3 class="m-t-10 m-b-0">Aktivitas Eksternal</h3>
                    <small>Terakhir diubah di SAP: {{ $externalActivities['last_updated'] }}</small>
                    <div class="media media">
                        <div class="media-body">
                            <dl class="dl-horizontal">
                                @foreach ($externalActivities['data'] as $externalActivity)
                                <dt>{{ $externalActivity->BEGDA }} - {{ $externalActivity->ENDDA }}</dt>
                                <dd><b>{{ $externalActivity->ZZPOSISI }}</b></dd>
                                <dt></dt>
                                <dd class="m-b-10">
                                    {{ $externalActivity->ORGNM }}
                                    ({{ $externalActivity->T591S_STEXT }})
                                </dd>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                    <h3 class="m-t-10 m-b-0">Aktivitas Lainnya</h3>
                    <small>Terakhir diubah di SAP: {{ $others['last_updated'] }}</small>
                    <div class="media media">
                        <div class="media-body">
                            <dl class="dl-horizontal">
                                @foreach ($others['data'] as $other)
                                <dt>{{ $other->BEGDA }} - {{ $other->ENDDA }}</dt>
                                <dd><b>{{ $other->T591S_STEXT }}</b></dd>
                                <dt></dt>
                                <dd class="m-b-10">
                                    {{ $other->PTEXT_LINE1 }}
                                    {{ $other->PTEXT_LINE2 }}
                                </dd>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of activities tab  -->

            <!-- begin of download tab  -->
            <div class="tab-pane fade active in" id="tab-download">
                <div class="panel-body p-0">

                </div>
            </div>
            <!-- end of download tab  -->
        </div>
        <!-- begin of tab-content  -->
    </div>
</div>

@endcomponent
<!-- end page container -->
@endsection

@push('styles')
<!-- Selectize -->
<link href={{ url("/plugins/selectize/selectize.css") }} rel="stylesheet">
<link href={{ url("/plugins/selectize/selectize.bootstrap3.css") }} rel="stylesheet">
<!-- Pace -->
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
<style>
/*   
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.4
Version: 1.7.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v1.7/admin/
*/
@media print {
    .content,.page-header-fixed,body {
        padding: 0!important;
        margin: 0!important
    }

    .header,.sidebar,.theme-panel {
        display: none!important
    }
}
</style>
@endpush

@push('plugin-scripts')
<!-- Selectize -->
<script src={{ url("/plugins/selectize/selectize.min.js") }}></script>
@endpush

@push('custom-scripts')
@include('scripts._save-tab-state-script')
@endpush

@push('on-ready-scripts')
TabStatePlugins.init();
@endpush