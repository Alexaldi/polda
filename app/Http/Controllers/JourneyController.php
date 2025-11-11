<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\JourneyService;
use Illuminate\Http\Request;

class JourneyController extends Controller
{
     protected $journeyService;

    public function __construct(JourneyService $journeyService)
    {
        $this->journeyService = $journeyService;
    }

    public function store(Request $request, $reportId)
    {
        // checkpoint dummy
        return back()->with('success', 'Journey dummy diterima (report ID: '.$reportId.')');
    }
}
