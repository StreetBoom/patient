<?php

namespace App\Services;

use App\Http\Requests\PatientRequest;
use App\Jobs\ProcessPatient;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PatientService
{
    public static function createPatient(PatientRequest $request): mixed
    {
        $dataPatient = $request->validated();

        $birthdate = Carbon::parse($dataPatient['birthdate']);
        $now = Carbon::now();
        $age = $birthdate->diff($now);
        $ageType = 'год';
        $calculatedAge = $age->y;

        if ($age->y == 0) {
            if ($age->m == 0) {
                $ageType = 'день';
                $calculatedAge = $age->d;
            } else {
                $ageType = 'месяц';
                $calculatedAge = $age->m;
            }
        }

        $patient = Patient::create([
            'first_name' => $dataPatient['first_name'],
            'last_name' => $dataPatient['last_name'],
            'birthdate' => $dataPatient['birthdate'],
            'age' => $calculatedAge,
            'age_type' => $ageType,
        ]);

        Cache::put("patient_{$patient->id}", $patient, 300);

        ProcessPatient::dispatch($patient);

        return $patient;
    }

    public static function getAllPatients(): mixed
    {
        return Cache::remember('patients_list', 300, function () {
            return Patient::all()->map(function ($patient) {
                return [
                    'name' => $patient->first_name.' '.$patient->last_name,
                    'birthdate' => $patient->birthdate,
                    'age' => $patient->age.' '.$patient->age_type,
                ];
            });
        });
    }
}
