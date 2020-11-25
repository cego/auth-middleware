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
    "in_memory"        => false,
    "uuid_primary_key" => false,     // Must default to false to be non-breaking

];