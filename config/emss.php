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
      'start_date'=> env('MODULES_LEAVES_STARTDATE', 'new Date(new Date().getFullYear(), new Date().getMonth(), 1)'),
      'end_date' => env('MODULES_LEAVES_ENDDATE', 'new Date(new Date().getFullYear(), new Date().getMonth() + 4, 5)'),
    ],
    'permits' => [
      'text' => 'Izin',
      'start_date'=> env('MODULES_PERMITS_STARTDATE', 'new Date(new Date().getFullYear(), new Date().getMonth(), 1)'),
      'end_date' => env('MODULES_PERMITS_ENDDATE', 'new Date(new Date().getFullYear(), new Date().getMonth() + 4, 5)'),      
    ],
    'overtimes' => [
      'text' => 'Lembur',
      'start_date'=> env('MODULES_OVERTIMES_STARTDATE','-3d'),
      'end_date' => env('MODULES_OVERTIMES_ENDDATE', 'new Date(new Date().getFullYear(), new Date().getMonth() + 4, 5)'),      
    ],
    'time_events' => [
      'text' => 'Tidak Slash', 
      'start_date'=> env('MODULES_TIME_EVENTS_STARTDATE', 'new Date(new Date().getFullYear(), new Date().getMonth(), 1)'),
      'end_date' => env('MODULES_TIME_EVENTS_ENDDATE', 'new Date(new Date().getFullYear(), new Date().getMonth() + 4, 5)'),     
    ],
  ],

  // config di module personnel services
  'personnel_service' => [
    'page_length' => env('PERSONNEL_SERVICE_PAGELENGTH', 10),
  ],

  // alamat folder untuk activity
  'activity' => [
    'dir' => env('ACTIVITY_DIR', '/nfs/interface/LapActivity/'),
    'archive' => env('ACTIVITY_ARCHIVE', '/nfs/interface/LapActivity/Archive/'),
  ],

];
