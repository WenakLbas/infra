<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\EtatSatisfaction;

class EtatSatisfactionController extends Controller
{
    public function etatSatisfactions()
    {
        $satis = DB::table('etat_satisfactions')
            ->get();
        return response()->json($satis, 200);
    }

    public function findEtatSatisfaction($id)
    {
        try{
            $satisfa = DB::table('etat_satisfactions')
                ->join('admins','etat_satisfactions.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'etat_satisfactions.*')
                ->where(['etat_satisfactions.id' => $id])
                ->get();
            if (!$satisfa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $satisfa
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeEtatSatisfaction(Request $request)
    {
        $this->validate($request, [
            'id_satifaction' => 'required',
            'id_etat_infra' => 'required',
        ]);

        $satisfa = new EtatSatisfaction();
        $satisfa->admin_id = $request->admin_id;
        $satisfa->super_id = $request->super_id;
        $satisfa->id_satifaction = $request->id_satifaction;
        $satisfa->id_etat_infra = $request->id_etat_infra;

        if ($satisfa->save())
            return response()->json( $satisfa->toArray(), 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateEtatSatisfaction(Request $request, $id) {
        try {
            $satisfadb = EtatSatisfaction::where('id', $request->id)->first();
            $satisfa = array();
            $satisfa['id_satifaction'] = is_null($request->id_satifaction) ? $satisfadb->id_satifaction : $request->id_satifaction;
            $satisfa['id_etat_infra'] = is_null($request->id_etat_infra) ? $satisfadb->id_etat_infra : $request->id_etat_infra;
            $satisfa['admin_id'] = $request->admin_id;
            $satisfa['super_id'] = $request->super_id;
            $updated = DB::table('etat_satisfactions')->where('id', $request->id)->update($satisfa);
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


    public function deleteEtatSatisfaction($id) {
        try {
            $satisfadb = EtatSatisfaction::where('id', $id)->get();
            if($satisfadb) {
                EtatSatisfaction::where('id', $id)->delete();
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
