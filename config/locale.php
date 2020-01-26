<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Locale Settings
    |--------------------------------------------------------------------------
    */

    'enabled' => false,

    'languages' => [
        /*
         * Key is the Laravel locale code
         * Index 0 of sub-array is the Carbon locale code
         * Index 1 of sub-array is the PHP locale code for setlocale()
         * Index 2 of sub-array is whether or not to use RTL (right-to-left) css for this language
         */
        'en' => ['en', 'en_US', false],
    ],
];
