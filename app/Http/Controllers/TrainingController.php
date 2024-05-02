<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Signatory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainings = Training::all();
        $signatories = Signatory::all();

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
            'director_id' => $request->director_id,
            'hr_head_id' => $request->hr_head_id,
        ]);

        return redirect()->back();

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
