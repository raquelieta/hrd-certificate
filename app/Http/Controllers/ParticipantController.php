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

        return Inertia::render('Participant/IndexParticipant', ['training' => $training]);
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

        // Loop through each row and column to extract data
        for ($row = 1; $row <= $highestRow; $row++) {
            $rowData = [];
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $cellValue = $sheet->getCell($col . $row)->getValue();
                $rowData[] = $cellValue;
            }
            $data[] = $rowData;
        }
        
        dd($data);
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
        return response()->json(['data' => $data]);
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
