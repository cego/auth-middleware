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

    "model"                   => App\Models\User::class,
    "remote_user_column"      => "username",
    "remote_user_uuid_column" => "uuid",
    "in_memory"               => false,

];