<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PendingApprovalController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('PendingApproval');
    }
}