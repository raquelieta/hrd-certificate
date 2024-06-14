<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Training;
use App\Models\Signatory;
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
        $approvedBy = Signatory::where('is_approved',1)->first();
        $attestedBy = Signatory::where('is_attested',1)->first();
        $approvedByFullName = strtoupper($approvedBy->first_name . ' ' . $approvedBy->middle_initial . ' ' .$approvedBy->last_name);
        $attestedByFullName = strtoupper($attestedBy->first_name . ' ' . $attestedBy->middle_initial . ' ' .$attestedBy->last_name);
        $pdf = new TCPDF('P', 'mm', 'A4',true,'UTF-8',false);
        $pdf->SetMargins(10,10,10,10,true);
        $pdf->SetAuthor('Civil Service Commission Regional Office X');
        $pdf->SetTitle('Certificate of Completion');
        $pdf->SetAutoPageBreak(false,0);

        foreach ($participants as $participant) {
            $pdf->AddPage();

            // Determine the formatted date range
            $startDate = Carbon::parse($training->training_start);
            $endDate = Carbon::parse($training->training_end);

            if ($startDate->isSameMonth($endDate) && $startDate->isSameYear($endDate)) {
                $formattedDateRange = $startDate->format('F j') . '-' . $endDate->format('j, Y');
            } else {
                $formattedDateRange = $startDate->format('F j, Y') . ' - ' . $endDate->format('F j, Y');
            }

            $html = '
                <div style=" width:100%;">
                    <div style="align-items:center; padding: 0; border: 1px solid #e6e6;">

                        <div style="align-items:center; text-align:center; font-size:14px;">
                         <img src="'.public_path('assets/img/0-csclogo.png').'" width="70" />
                            <p>This</p>
                            <h1 style="font-size: 24px; font-family:Embassy BT; margin: 0; padding: 0;">CERTIFICATE OF COMPLETION</h1>  
                            <p>is awarded to
                            <h2 style="margin: 0; padding: 0; font-family:embassyn; font-size:36px;">' . $participant->cert_name . '</h2>
                            <span style="margin-top: -10px; padding: 0;">for having successfully completed the </span></p>
                            <p><span style="font-weight:bold; margin: 0 auto; padding: 0;  font-size: 16px; max-width:60%;">' . $training->description . '</span><br>
                            <span style="font-size:12px; font-style:italic;"> [credit of '. $this->numberToWords($training->training_hours) . '('.$training->training_hours.') '.' training hours, '.$training->training_type['value'].' Program]
                            </span></p>
                            <p  width="30">held on ' . $formattedDateRange  . ' at</p>
                            <p style="max-width:60%; margin:0 auto; word-wrap:break-word;">' . $training->training_address . '</p>
                            <p> Given this ' .$this->formatDateWithOrdinal($training->issuance_date) . '</p>
                            <p></p>

                            <p><span style="font-weight:bold; top:10em; font-size:18px; ">' . $approvedByFullName . '</span><span> <br>' .$approvedBy->position . '</span></p>
                            <p style="margin-top:5px; margin-bottom:50px;">Attested by:</p>
                            <p></p>
                            <p><span style="font-weight:bold; margin-top:10px; font-size:18px; display:flex;">' . $attestedBy->full_name . '</span><span> <br>' . $attestedBy->position . '</span>
                                <span><br> '. $attestedBy->designation. '</span>
                                <span style="border:1px solid #e6e6"></span>
                            </p>
                            
                        </div>
                        
                    </div>
                    
                    
                </div>';    
            $pdf->writeHTML($html, true, false, true, false, '');

            $pdf->Image(public_path('assets/img/0-csclogo.png'), 20, 250, 30); // X, Y, Width in mm
        }
        
        
        return response($pdf->Output($training->name . ' Certificates.pdf', 'S'))->header('Content-Type', 'application/pdf');
    }

    public function numberToWords($string){
        switch($string){
            case 1:
                return "one";
            break;

            case 2:
                return "two";
            break;

            case 3:
                return "three";
            break;

            case 4:
                return "four";
            break;

            case 5:
                return "five";
            break;

            case 8:
                return "eight";
            break;

            case 16:
                return "sixteen";
            break;
        };
    }


    function formatDateWithOrdinal($date)
    {
        $carbonDate = Carbon::parse($date);

        $day = $carbonDate->day;
        $ordinalDay = $day . $this->getOrdinalSuffix($day);

        $monthAndYear = $carbonDate->format('F Y');

        return "{$ordinalDay} Day of {$monthAndYear}";
    }

    function getOrdinalSuffix($number)
    {
        $suffix = 'th';

        if (!in_array(($number % 100), [11, 12, 13])) {
            switch ($number % 10) {
                case 1:
                    $suffix = 'st';
                    break;
                case 2:
                    $suffix = 'nd';
                    break;
                case 3:
                    $suffix = 'rd';
                    break;
            }
        }

        return $suffix;
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
