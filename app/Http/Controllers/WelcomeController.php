<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    function __invoke()
    {
        return view('welcome');
    }
}