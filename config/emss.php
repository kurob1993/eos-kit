<?php

return [
  // flow_id yang digunakan untuk setiap proses yang ada
  // seharusnya disimpan di DATABASE?
  'flows' => [
    'absences' => 1,
    'attendances' => 1,
    'attendance_quotas' => 1,
    'time_events' => 1
  ],

  // format tanggal
  'date_format' => 'd.m.Y',

  // format tanggal dan jam
  'datetime_format' => 'd.m.Y H:i:s',

  // modules yang ada di emss
  'modules' => [
    'leaves' => [
      'text' => 'Cuti',      
    ],
    'permits' => [
      'text' => 'Izin',      
    ],
    'overtimes' => [
      'text' => 'Lembur',      
    ],
    'time_events' => [
      'text' => 'Tidak Slash',      
    ],
  ],

  // config di module personnel services
  'personnel_service' => [
    'page_length' => env('PERSONNEL_SERVICE_PAGELENGTH', 10),
  ],
];
