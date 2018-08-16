<?php

return [
  // flow_id yang digunakan untuk setiap proses yang ada
  // seharusnya disimpan di DATABASE?
  'flows' => [
    'absences' => 1,
    'attendances' => 1,
    'attendance_quotas' => 1
  ],

  // format tanggal
  'date_format' => 'd.m.Y',

  // format tanggal dan jam
  'datetime_format' => 'd.m.Y H:i:s',
];
