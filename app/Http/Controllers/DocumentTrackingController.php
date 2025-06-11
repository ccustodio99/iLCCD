<?php

namespace App\Http\Controllers;

class DocumentTrackingController extends Controller
{
    public function incoming()
    {
        return view('documents.tracking.incoming');
    }

    public function outgoing()
    {
        return view('documents.tracking.outgoing');
    }

    public function forApproval()
    {
        return view('documents.tracking.for_approval');
    }

    public function tracking()
    {
        return view('documents.tracking.tracking');
    }

    public function reports()
    {
        return view('documents.tracking.reports');
    }
}
