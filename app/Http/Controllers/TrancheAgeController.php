<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\TrancheAge;


class TrancheAgeController extends Controller
{
    public function trancheAges()
    {
        $ages = DB::table('tranche_ages')
            ->get();
        return response()->json($ages);
    }

    public function findTrancheAge($id)
    {
        try{
            $age = DB::table('tranche_ages')
                ->join('admins','tranche_ages.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'tranche_ages.*')
                ->where(['tranche_ages.id' => $id])
                ->get();
            if (!$age) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $age
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeTrancheAge(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',


        ]);

        $age = new TrancheAge();
        $age->admin_id = $request->admin_id;
        $age->super_id = $request->super_id;
        $age->libelle = $request->libelle;


        if ($age->save())
            return response()->json([
                'success' => true,
                'text' => $age->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateTrancheAge(Request $request, $id) {
        try {
            $agedb = TrancheAge::where('id', $request->id)->first();
            $age = array();
            $age['libelle'] = is_null($request->libelle) ? $agedb->libelle : $request->libelle;
            $age['admin_id'] = $request->admin_id;
            $age['super_id'] = $request->super_id;
            $updated = DB::table('tranche_ages')->where('id', $request->id)->update($age);
            if ($updated){
                return response()->json([
                    'success' => true,
                    'message' => "L'enregistrement a ete modifier avec successe...!"
                ], 200);
            }
        }catch (\Exception $e){
            return response()->json([
                "message" => $e->getMessage()
                //"message" => 'Cet enregistrement n\'existe pas'
            ], 404);
        }
    }


    public function deleteTrancheAge($id) {
        try {
            $raisnEnqtdb = TrancheAge::where('id', $id)->get();
            if($raisnEnqtdb) {
                TrancheAge::where('id', $id)->delete();
                return response()->json([
                    "message" => 'Cet enregistrement est supprimé définitivement'
                ], 202);

            } else {
                return response()->json([
                    "message" => 'Cet enregistrement n\'existe pas'
                    // "message" => $blog
                ], 404);
            }
        }catch (\Exception $e){
            return response()->json([
                "message" => 'Cet enregistrement ne pas etre supprimer'
                //'message' => $e->getMessage()
            ], 400);
        }
    }
}

