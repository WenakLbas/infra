<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\EtatAutoriteDenonciation;

class EtatAutoriteDenonciationController extends Controller
{
    public function denonciations()
    {
        $provinces = DB::table('etat_denonciations')
            ->get();
        return response()->json($provinces, 200);
    }


    public function findEtatDenonciation($id)
    {
        try{
            $provinces = DB::table('etat_denonciations')
                ->join('admins','etat_denonciations.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'etat_denonciations.*')
                ->where(['etat_denonciations.id' => $id])
                ->get();
            if (!$provinces) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $provinces
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeEtatDenonciation(Request $request)
    {
        $this->validate($request, [
            'id_autosaisie' => 'required',
            'id_etat_infra' => 'required',
        ]);

        $etatDenoce = new EtatAutoriteDenonciation();
        $etatDenoce->admin_id = $request->admin_id;
        $etatDenoce->super_id = $request->super_id;
        $etatDenoce->id_autosaisie = $request->id_autosaisie;
        $etatDenoce->id_etat_infra = $request->id_etat_infra;

        if ($etatDenoce->save())
            return response()->json([
                'success' => true,
                'text' => $etatDenoce->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateEtatDenonciation(Request $request, $id) {
        try {
            $etatDenocedb = EtatAutoriteDenonciation::where('id', $request->id)->first();
            $etatDenoce = array();
            $etatDenoce['id_autosaisie'] = is_null($request->id_autosaisie) ? $etatDenocedb->id_autosaisie : $request->id_autosaisie;
            $etatDenoce['id_etat_infra'] = is_null($request->id_etat_infra) ? $etatDenocedb->id_etat_infra : $request->id_etat_infra;
            $etatDenoce['admin_id'] = $request->admin_id;
            $etatDenoce['super_id'] = $request->super_id;
            $updated = DB::table('etat_denonciations')->where('id', $request->id)->update($etatDenoce);
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


    public function deleteEtatDenonciation($id) {
        try {
            $provincdb = EtatAutoriteDenonciation::where('id', $id)->get();
            if($provincdb) {
                EtatAutoriteDenonciation::where('id', $id)->delete();
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



