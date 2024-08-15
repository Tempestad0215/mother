<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    //

    /**
     * Devolver la vista de todos los reporte
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Reports/Index');
    }
}
