<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class GuestController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('GuestHome');
    }
}