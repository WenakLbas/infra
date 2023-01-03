<?php

namespace App\Http\Controllers;

use DB;
use App\Models\EtatEnqte;
use Illuminate\Http\Request;

class EtatEnqteController extends Controller
{
    public function etatEnqtes()
    {
        $enqtes = DB::table('etat_enqtes')
            ->get();
        return response()->json($enqtes, 200);
    }

    public function findEtatEnqte($id)
    {
        try{
            $enqte = DB::table('etat_enqtes')
                ->join('admins','etat_enqtes.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'etat_enqtes.*')
                ->where(['etat_enqtes.id' => $id])
                ->get();
            if (!$enqte) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $enqte
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeEtatEnqte(Request $request)
    {
        $this->validate($request, [
            'id_enqte' => 'required',
            'id_autosaisie' => 'required',
            'id_etat_infra' => 'required',
        ]);

        $enqte = new EtatEnqte();
        $enqte->admin_id = $request->admin_id;
        $enqte->super_id = $request->super_id;
        $enqte->id_enqte = $request->id_enqte;
        $enqte->id_autosaisie = $request->id_autosaisie;
        $enqte->id_etat_infra = $request->id_etat_infra;

        if ($enqte->save())
            return response()->json( $enqte->toArray(), 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateEtatEnqte(Request $request, $id) {
        try {
            $enqtedb = EtatEnqte::where('id', $request->id)->first();
            $enqte = array();
            $enqte['id_enqte'] = is_null($request->id_enqte) ? $enqtedb->id_enqte : $request->id_enqte;
            $enqte['id_autosaisie'] = is_null($request->id_autosaisie) ? $enqtedb->id_autosaisie : $request->id_autosaisie;
            $enqte['id_etat_infra'] = is_null($request->id_etat_infra) ? $enqtedb->id_etat_infra : $request->id_etat_infra;
            $enqte['admin_id'] = $request->admin_id;
            $enqte['super_id'] = $request->super_id;
            $updated = DB::table('etat_enqtes')->where('id', $request->id)->update($enqte);
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


    public function deleteEtatEnqte($id) {
        try {
            $enqtedb = EtatEnqte::where('id', $id)->get();
            if($enqtedb) {
                EtatEnqte::where('id', $id)->delete();
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
