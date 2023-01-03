<?php

namespace App\Http\Controllers;

use DB;
use App\Models\RaisnSatisfaction;

use Illuminate\Http\Request;

class RaisnSatisfController extends Controller
{
    public function raisonsatisfactions()
    {
        $raisnsatisf = DB::table('raison_satisfactions')
            ->get();
        return response()->json($raisnsatisf);
    }

    public function findRaisnSatisf($id)
    {
        try{
            $raisnsatisf = DB::table('raison_satisfactions')
                ->join('admins','raison_satisfactions.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'raison_satisfactions.*')
                ->where(['raison_satisfactions.id' => $id])
                ->get();
            if (!$raisnsatisf ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $raisnsatisf
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeRaisnSatisf(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',
            'type_satisfaction' => 'required',


        ]);

        $raisnsatisf = new RaisnSatisfaction();
        $raisnsatisf->admin_id = $request->admin_id;
        $raisnsatisf->super_id = $request->super_id;
        $raisnsatisf->libelle = $request->libelle;
        $raisnsatisf->type_satisfaction = $request->type_satisfaction;


        if ($raisnsatisf->save())
            return response()->json([
                'success' => true,
                'text' => $raisnsatisf->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateraisnSatisf(Request $request, $id) {
        try {
            $raisnsatisfdb = RaisnSatisfaction::where('id', $request->id)->first();
            $raisnsatisf = array();
            $raisnsatisf['libelle'] = is_null($request->libelle) ? $raisnsatisfdb->libelle : $request->libelle;
            $raisnsatisf['type_satisfaction'] = is_null($request->type_satisfaction) ? $raisnsatisfdb->type_satisfaction : $request->type_satisfaction;
            $raisnsatisf['admin_id'] = $request->admin_id;
            $raisnsatisf['super_id'] = $request->super_id;
            $updated = DB::table('raison_satisfactions')->where('id', $request->id)->update($raisnsatisf);
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


    public function deleteRaisnSatisf($id) {
        try {
            $raisnSatisfdb = RaisnSatisfaction::where('id', $id)->get();
            if($raisnSatisfdb) {
                RaisnSatisfaction::where('id', $id)->delete();
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

