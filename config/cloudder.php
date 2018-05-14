<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary API configuration
    |--------------------------------------------------------------------------
    |
    | Before using Cloudinary you need to register and get some detail
    | to fill in below, please visit cloudinary.com.
    |
    */

    'cloudName'  => env('CLOUDINARY_CLOUD_NAME'),
    'apiKey'     => env('CLOUDINARY_API_KEY'),
    'apiSecret'  => env('CLOUDINARY_API_SECRET'),

    'scaling'    => [
        'format' => 'png',
        'width'  => 150,
        'height' => 150,
        'crop'   => 'fit',
        'effect' => null
    ],

];
