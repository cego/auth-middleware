<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Auth Middleware
    |--------------------------------------------------------------------------
    |
    | Here you can control which model and column to use for authentication
    |
    */

    "model"            => App\Models\User::class,
    "column"           => "username",
    "in-memory"        => false,
    "uuid-primary-key" => false,     // Must default to false to be non-breaking

];