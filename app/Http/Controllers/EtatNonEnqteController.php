<?php

namespace App\Http\Controllers;

use DB;
use App\Models\EtatNonEnqte;
use Illuminate\Http\Request;

class EtatNonEnqteController extends Controller
{
    public function etatNonEnqtes()
    {
        $nonenqutes = DB::table('etat_non_enqtes')
            ->get();
        return response()->json($nonenqutes, 200);
    }


    public function findEtatNonEnqte($id)
    {
        try{
            $nonenqute = DB::table('etat_non_enqtes')
                ->join('admins','etat_non_enqtes.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'etat_non_enqtes.*')
                ->where(['etat_non_enqtes.id' => $id])
                ->get();
            if (!$nonenqute) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $nonenqute
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeEtatNonEnqte(Request $request)
    {
        $this->validate($request, [
            'id_rsnon_enqte' => 'required',
            'id_etat_infra' => 'required',
        ]);

        $nonenqute = new EtatNonEnqte();
        $nonenqute->admin_id = $request->admin_id;
        $nonenqute->super_id = $request->super_id;
        $nonenqute->id_rsnon_enqte = $request->id_rsnon_enqte;
        $nonenqute->id_etat_infra = $request->id_etat_infra;

        if ($nonenqute->save())
            return response()->json($nonenqute->toArray(), 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateEtatNonEnqte(Request $request, $id) {
        try {
            $nonenqutedb = EtatNonEnqte::where('id', $request->id)->first();
            $nonenqute = array();
            $nonenqute['id_rsnon_enqte'] = is_null($request->id_rsnon_enqte) ? $nonenqutedb->id_rsnon_enqte : $request->id_rsnon_enqte;
            $nonenqute['id_etat_infra'] = is_null($request->id_etat_infra) ? $nonenqutedb->id_etat_infra : $request->id_etat_infra;
            $nonenqute['admin_id'] = $request->admin_id;
            $nonenqute['super_id'] = $request->super_id;
            $updated = DB::table('etat_non_enqtes')->where('id', $request->id)->update($nonenqute);
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


    public function deleteEtatNonEnqte($id) {
        try {
            $gdeavuedb = EtatNonEnqte::where('id', $id)->get();
            if($gdeavuedb) {
                EtatNonEnqte::where('id', $id)->delete();
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
