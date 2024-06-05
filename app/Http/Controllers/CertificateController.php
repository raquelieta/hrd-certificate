<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Training;
use TCPDF;
use Carbon\Carbon;

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
        $training = Training::where('id',$request->training_id)->first();

        $pdf = new TCPDF('P', 'mm', 'A4',true,'UTF-8',false);
        $pdf->SetMargins(20,1,20,true);
        $pdf->SetAuthor('Civil Service Commission Regional Office X');
        $pdf->SetTitle('Certificate of Completion');
        $pdf->SetAutoPageBreak(false,0);

        foreach ($participants as $participant) {
            $pdf->AddPage();
            $html = '
                <div style="width:50%; margin: 0; padding: 0;">
                    <div style="align-items:center; border-width:4px; border-style:solid; margin: 0; padding: 0;">
                        <div style="justify-items:center; align-items:center; text-align:center; margin: 0; padding: 0;">
                            <img src="'.public_path('assets/img/0-csclogo.png').'" width="60" />
                        </div>
                        <div style="align-items:center; text-align:center; font-size:14px; margin: 0; padding: 0;">
                            <p style="margin: 0; padding: 0;">This</p>
                            <h1 style="font-size: 24px; font-family:Embassy BT; margin: 0; padding: 0;">CERTIFICATE OF COMPLETION</h1>  
                            <p style="margin: 0; padding: 0;">is awarded to</p>

                            <h2 style="letter-spacing: 1px; margin: 0; padding: 0; font-family:embassyn; font-size:36px;">' . $participant->cert_name . '</h2>
                            <p style="margin: 0; padding: 0;">for having successfully completed the</p>
                            <p style="font-weight:bold; margin: 0; padding: 0;">' . $training->description . '</p>
                            <p style="margin: 0; padding: 0;">[credit of'.'('.$training->training_hours.') training hours, '.$training->training_type->value.' Program]</p>
                            <p>held on 
                        </div>';
            $pdf->writeHTML($html, true, false, true, false, '');
        }
        
        
        return response($pdf->Output($training->name . ' Certificates.pdf', 'S'))->header('Content-Type', 'application/pdf');
    }

    public function numberToWords($string){
        switch($string){
            case 1:
                return "one";
            break;

            case 4:
                return "four";
            break;
        }
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
