<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $this->authorize('view', Report::class);
        $reports = Report::all();

        if ($reports){
            return response()->json([
                'report' => $reports,
            ],200);
        }
        else{
            return response()->json([
                'message' => 'Здесь пока нет отчетов :('
            ],404);
        }
    }
}
