<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Signatory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;
use Illuminate\Support\Facades\Mail;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainings = Training::all();
        $signatories = $signatory = Signatory::where('is_approved',1)->orWhere('is_attested',1)->orWhere('is_msd',1)->get();

        return Inertia::render('Dashboard', ['trainings' => $trainings, 'signatories' => $signatories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $trainingStartDate = Carbon::parse($request->training_start);
        $getStartDate = $trainingStartDate->format('Y-m-d');
        $trainingEndDate = Carbon::parse($request->training_end)->format('Y-m-d');

        $trainingCount = Training::whereYear('training_start', $trainingStartDate->year)
        ->whereMonth('training_start', $trainingStartDate->month)
        ->count();

        $getTrainingCode = "TR-". $trainingStartDate->format('Y-m')."-". $trainingCount+1;

        $training = Training::create([
            'training_code' => $getTrainingCode,
            'training_name' => $request->training_name,
            'description' => $request->description,
            'training_start' => $getStartDate,
            'training_end' => $trainingEndDate,
            'training_hours' => $request->training_hours,
            'training_type' => $request->training_type,
            'training_venue' => $request->training_venue,
            'training_address' => $request->training_address,
            'training_speakers' => $request->training_speakers,
            'issuance_date' => $request->issuance_date,
            'director_id' => $request->director_id,
            'hr_head_id' => $request->hr_head_id,
            'qr_code_path' => $request->qr_code_path,
        ]);

        $this->generateQRCode($request->qr_code_path,$getTrainingCode, $request->training_name,);

        return redirect()->back();

    }

    public function generateQRCode($qrCodeContent,$trainingCode,$trainingName)
    {
        $writer = new PngWriter();
        
        $qrCode = QrCode::create($qrCodeContent)
                    ->setEncoding(new Encoding('UTF-8'))
                    ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
                    ->setSize(300)
                    ->setMargin(10)
                    ->setBackgroundColor(new Color(255,255,255));

        $logoPath = public_path('assets/img/0-csclogo.png');

        $logo = Logo::create($logoPath)
                ->setResizeTowidth(50)
                ->setPunchoutBackground(true);
        
        $label = Label::create($trainingName)
                ->setTextColor(new Color(255,0, 0));

        $resultQr = $writer->write($qrCode,$logo, $label);
        header('Content-Type: '.$resultQr->getMimeType());

        $writer->validateResult($resultQr, $qrCodeContent);

        $baseDirectory = storage_path('app\public\qrcodes');
        
        if (!is_dir($baseDirectory)) {
            mkdir($baseDirectory, 0775, true);
        }

        file_put_contents($trainingCode.'.png',$qrCodeContent);
        $qrCodeImage = $resultQr->getDataUri();
      
        $resultQr->saveToFile($baseDirectory);

        $relativePath = $baseDirectory . $trainingCode. '.png';

        return $relativePath;
    }

    /**
     * Display the specified resource.
     */
    public function show(Training $training)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Training $training)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Training $training)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Training $training)
    {
        //
    }
}
