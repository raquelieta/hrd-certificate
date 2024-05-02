<?php

namespace App\Http\Controllers;

use App\Models\Signatory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SignatoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $signatory = Signatory::where('is_approved',1)->orWhere('is_attested',1)->orWhere('is_msd',1)->get();

        return Inertia::render('Signatory/IndexSignatory',['signatories' => $signatory]);
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
        $approval = 0;
        $attest = 0;
        $adminOfficer = 0;

        if($request->signatoryType['index'] == 0){
            $approval = 1;
        }
        else if($request->signatoryType['index'] == 2){
            $adminOfficer = 1;
        }
        else{
            $attest =1;
        }

        $signatory = Signatory::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_initial' => $request->middle_initial,
            'position' => $request->position,
            'designation' => $request->designation,
            'is_approved' => $approval,
            'is_attested' => $attest,
            'is_msd' => $adminOfficer,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Signatory $signatory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Signatory $signatory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Signatory $signatory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Signatory $signatory)
    {
        //
    }
}
