<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Models\Participant;
use TCPDF;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $participants = Participant::where('training_id',$request->training_id)->with('training')->get();

        dd($participants);
        $pdf = new TCPDF('P', 'mm', 'A4',true,'UTF-8',false);
        $pdf>SetMargins(20,20,20,true);
        $pdf->SetAuthor('Civil Service Commission Regional Office X');
        $pdf->SetTitle('Certificate of Completion');
        $pdf->SetAutoPageBreak(false,0);
        $pdf->AddPage();

        $pdf->writeHTML(view('certificate'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certificate $certificate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        //
    }
}
