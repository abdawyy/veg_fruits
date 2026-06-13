<?php

return [

    'format' => env('PDF_FORMAT', 'A4'),

    'default_font' => env('PDF_DEFAULT_FONT', 'dejavusans'),

    'margins' => [
        'left' => 12,
        'right' => 12,
        'top' => 12,
        'bottom' => 12,
    ],

    'temp_dir' => storage_path('app/mpdf'),

];
