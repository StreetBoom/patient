<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Services\PatientService;

class PatientController extends Controller
{
    public function create()
    {
        return view('patients.create');
    }

    public function store(PatientRequest $request)
    {
        $patient = PatientService::createPatient($request);

        return redirect()->route('patient.index');
    }

    public function index()
    {
        $patients = PatientService::getAllPatients();

        return view('patients.index', ['patients' => $patients]);
    }
}
