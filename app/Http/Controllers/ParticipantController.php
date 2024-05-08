<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Training;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel_IOFactory;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($trainingId)
    {
        $training = Participant::with('training')->first();

        return Inertia::render('Participant/IndexParticipant', ['training' => $training, 'training_id' => $trainingId]);
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
        $file = $request->file;

        $spreadsheet = IOFactory::load($file);
        
        $sheet = $spreadsheet->getActiveSheet();
        
        $highestRow = $sheet->getHighestDataRow();
        $highestColumn = $sheet->getHighestDataColumn();
 
        // Initialize an array to store the data
        $data = [];
        $civil_status=null;

        // Loop through each row and column to extract data
        for ($row = 2; $row <= $highestRow; $row++) {
            $cellValueB = $sheet->getCell('B'.$row)->getCalculatedValue();
            $cellValueC = $sheet->getCell('C'.$row)->getCalculatedValue();
            $cellValueD = $sheet->getCell('D'.$row)->getCalculatedValue();
            $cellValueE = $sheet->getCell('E'.$row)->getCalculatedValue();
            $cellValueF = $sheet->getCell('F'.$row)->getCalculatedValue();
            $cellValueG = $sheet->getCell('G'.$row)->getCalculatedValue();
            $cellValueH = $sheet->getCell('H'.$row)->getCalculatedValue();
            $cellValueI = $sheet->getCell('I'.$row)->getCalculatedValue();
            $cellValueJ = $sheet->getCell('J'.$row)->getCalculatedValue();
            $cellValueK = $sheet->getCell('K'.$row)->getCalculatedValue();
            $cellValueL = $sheet->getCell('L'.$row)->getCalculatedValue();
            $cellValueM = $sheet->getCell('M'.$row)->getCalculatedValue();
            $cellValueN = $sheet->getCell('N'.$row)->getCalculatedValue();
            $cellValueO = $sheet->getCell('O'.$row)->getCalculatedValue();
            $cellValueP = $sheet->getCell('P'.$row)->getCalculatedValue();
            $cellValueQ = $sheet->getCell('Q'.$row)->getCalculatedValue();

            if($cellValueH == ''){
                $civil_status = 'Single';
            }
            // $rowData = [];
            // for ($col = 'A'; $col <= $highestColumn; $col++) {
            //     $cellValue = $sheet->getCell($col . $row)->getValue();
            //     $rowData[] = $cellValue;
            // }
            // $data[] = $rowData;
            $participant = Participant::create([
                'training_id' => $request->training_id,
                'last_name' => $cellValueC,
                'first_name' => $cellValueD,
                'middle_initial' => $cellValueE,
                'suffix' => $cellValueF,
                'position' => $cellValueG,
                'civil_status' => $civil_status,
                'employment_status' => $cellValueI,
                'rank' => $cellValueJ,
                'years_in_service' => $cellValueK,
                'province' => $cellValueL,
                'sex' => $cellValueM,
                'government_sector' => $cellValueN,
                'agency_name' => $cellValueO,
                'personal_number' => $cellValueP,
                'personal_email' => $cellValueQ,
            ]);
        }
        // dd($data);
        
        // for ($i =1; $i < count($data); $i++){
        //     $value = $data[$i];

        //     
        // }
        // $innerData = [];
        // for ($j = 0; $j < count($data[0]); $j++) {
        //     $columnData = [];
        //     for ($i = 0; $i < count($data); $i++) {
        //         $columnData[] = $data[$i][$j];
        //     }
        //     $innerData[] = $columnData;
        // }
        // dd($innerData);

            

        // Process the data as needed
        // For example, you can store it in the database, display it, etc.

        // Return a response
        // return response()->json(['data' => $data]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participant $participant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Participant $participant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        //
    }
}
