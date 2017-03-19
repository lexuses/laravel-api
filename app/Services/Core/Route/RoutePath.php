<?php

namespace App\Services\Core\Route;

class RoutePath
{
    public static function get($name)
    {
        return base_path('app/Modules/' . $name . '/Routes/routes.php');
    }
}