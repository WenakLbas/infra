<?php

namespace App\Http\Controllers;

use DB;
use File;
use Excel;
use Illuminate\Http\Request;
use App\Models\Infractions;
use App\Exports\CrimeExport;
use App\Exports\ViolenceExport;
use App\Exports\InfractionsExport;
use App\Exports\InfractionExport;
use App\Exports\DeontologieExport;
use App\Imports\InfractionsImport;
use Illuminate\Support\Facades\Validator;

class InfraImportController extends Controller
{
    public function infractions(){
        $infractions = Infractions::get();
        return view('infractions', ['infractions' => $infractions]);
    }

    public function importInfractionsFile(Request $request){

        /*
        $request->validate([
            'infractionsFile' => 'required|mimes:xls, xlsx, csv'
        ]);
        */

        $validator = Validator::make(
            [
                'file'      => $request->infractionsFile,
                'extension' => strtolower($request->infractionsFile->getClientOriginalExtension()),
            ],
            [
                'file'          => 'required',
                'extension'      => 'required|in:xlsx,xls',
            ]

        );


        //dd($request->infractionsFile);


        if($request->hasFile('infractionsFile'))
        {
            // dd($request->infractionsFile);
            $path = request()->file('infractionsFile');
            Excel::import(new InfractionsImport, $path);
            return redirect()->back()->with('success', 'Données importées avec succes...!');
        }
    }

    public function infractionsExport(){
        return Excel::download(new InfractionsExport(), 'infractions.xlsx');
    }


    public function crimeInfrasFile(){
        return Excel::download(new CrimeExport(), 'etat_infraction-cime.xlsx');
    }
    public function violenceInfrasFile(){
        return Excel::download(new ViolenceExport(), 'etat_infraction-violence.xlsx');
    }
    public function deontologieInfrasFile(){
        return Excel::download(new DeontologieExport(), 'etat_infraction-deontologie.xlsx');
    }

}
