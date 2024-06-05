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
        $participants = Participant::where('training_id',$trainingId)->with('training')->get();

        return Inertia::render('Participant/IndexParticipant', ['participants' => $participants, 'training_id' => $trainingId]);
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
        $rank = null;
        $tenure = 0;
        $province=null;
        $sector = null;
        $agency = null;
        $personal_number = null;
        $personal_email = null;

        // Loop through each row and column to extract data
        for ($row = 2; $row <= $highestRow; $row++) {
            $cellValueB = $sheet->getCell('B'.$row)->getCalculatedValue();
            $cellValueC = $sheet->getCell('C'.$row)->getCalculatedValue();//Last Name
            $cellValueD = $sheet->getCell('D'.$row)->getCalculatedValue();//First Name
            $cellValueE = $sheet->getCell('E'.$row)->getCalculatedValue();//Middle Initial
            $cellValueF = $sheet->getCell('F'.$row)->getCalculatedValue();//Suffix
            $cellValueG = $sheet->getCell('G'.$row)->getCalculatedValue();//Position
            $cellValueH = $sheet->getCell('H'.$row)->getCalculatedValue();//Civil Status
            $cellValueI = $sheet->getCell('I'.$row)->getCalculatedValue();//Employment Status
            $cellValueJ = $sheet->getCell('J'.$row)->getCalculatedValue();//Rank
            $cellValueK = $sheet->getCell('K'.$row)->getCalculatedValue();//Tenure
            $cellValueL = $sheet->getCell('L'.$row)->getCalculatedValue();//Province
            $cellValueM = $sheet->getCell('M'.$row)->getCalculatedValue();//
            $cellValueN = $sheet->getCell('N'.$row)->getCalculatedValue();//Government Sector
            $cellValueO = $sheet->getCell('O'.$row)->getCalculatedValue();//Agency Name
            $cellValueP = $sheet->getCell('P'.$row)->getCalculatedValue();//Personal Number
            $cellValueQ = $sheet->getCell('Q'.$row)->getCalculatedValue();//Personal Email

            $last_name = strtolower($cellValueC);
            $first_name = strtolower($cellValueD);
            $middle_initial = strtolower($cellValueE);

            $civil_status = ($cellValueH == '') ? 'Single' : $cellValueH;
            $rank = ($cellValueJ == '') ? 'Non-supervisory' : $cellValueJ;
            $tenure = ($cellValueK == '') ? 1 : $cellValueK;
            $province = ($cellValueL == '') ? 'Misamis Oriental' : $cellValueL;
            $sector = ($cellValueN == '') ? 'Uncategorized' : $cellValueN;
            $agency = ($cellValueO == '') ? 'Not indicated' : $cellValueO;
            $personal_number = ($cellValueP == '') ? 0 : $cellValueP;
            $personal_email = ($cellValueQ == '') ? 'none' : $cellValueQ;

            $participant_name = strtoupper($cellValueC . ', ' . $cellValueD . ' ' . $cellValueE);

            $existingParticipant = Participant::whereRaw('UPPER(CONCAT(last_name, ", ", first_name, " ", middle_initial)) = ?', [$participant_name])
                                    ->where('training_id',$request->training_id)
                                    ->exists();

            if(!$existingParticipant){
                $participant = Participant::create([
                    'training_id' => $request->training_id,
                    'last_name' => ucwords($last_name),
                    'first_name' => ucwords($first_name),
                    'middle_initial' => ucwords($middle_initial),
                    'suffix' => $cellValueF,
                    'position' => $cellValueG,
                    'civil_status' => $civil_status,
                    'employment_status' => $cellValueI,
                    'rank' => $rank,
                    'years_in_service' => $tenure,
                    'province' => $province,
                    'sex' => $cellValueM,
                    'government_sector' => $sector,
                    'agency_name' => $agency,
                    'personal_number' => $personal_number,
                    'personal_email' => $personal_email,
                ]);
            }
            else{
                return Inertia::render('Participant/IndexParticipant',['message' => "Duplicate Entry"]);
            }
            
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
