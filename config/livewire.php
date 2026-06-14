<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Component Locations
    |--------------------------------------------------------------------------
    */

    'component_locations' => [
        resource_path('views/components'),
        resource_path('views/livewire'),
    ],

    'component_namespaces' => [
        'layouts' => resource_path('views/layouts'),
        'pages' => resource_path('views/pages'),
    ],

    'component_layout' => 'layouts::app',

    'component_placeholder' => null,

    'make_command' => [
        'type' => 'sfc',
        'emoji' => true,
        'with' => [
            'js' => false,
            'css' => false,
            'test' => false,
        ],
    ],

    'class_namespace' => 'App\\Livewire',

    'class_path' => app_path('Livewire'),

    'view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    |
    | Filament / Livewire upload to this disk first, then move to the field disk
    | (e.g. public/products). Use "public" so previews and permissions work on Windows.
    |
    */

    'temporary_file_upload' => [
        'disk' => env('LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK', 'public'),
        'rules' => ['file', 'image', 'max:5120'],
        'directory' => 'livewire-tmp',
        'middleware' => 'web',
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 30,
        'cleanup' => true,
    ],

    'render_on_redirect' => false,

    'legacy_model_binding' => false,

    'inject_assets' => true,

    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2299dd',
    ],

    'inject_morph_markers' => true,

    'smart_wire_keys' => true,

    'pagination_theme' => 'tailwind',

    'release_token' => 'a',

    'csp_safe' => false,

    'payload' => [
        'max_size' => 1024 * 1024,
        'max_nesting_depth' => 10,
        'max_calls' => 50,
        'max_components' => 200,
    ],

];
