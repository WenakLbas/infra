<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\EtatSourceOsc;


class EtatSourceOscController extends Controller
{
    public function etatSourceOscs()
    {
        $Judics = DB::table('etat_oscs')
            ->get();
        return response()->json($Judics, 200);
    }

    public function findEtatSourceOsc($id)
    {
        try{
            $Judic = DB::table('etat_oscs')
                ->join('admins','etat_oscs.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'etat_oscs.*')
                ->where(['etat_oscs.id' => $id])
                ->get();
            if (!$Judic) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $Judic
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeEtatSourceOsc(Request $request)
    {
        $this->validate($request, [
            'id_osc' => 'required',
            'id_etat_infra' => 'required',
        ]);

        $Judic = new EtatSourceOsc();
        $Judic->admin_id = $request->admin_id;
        $Judic->super_id = $request->super_id;
        $Judic->id_osc = $request->id_osc;
        $Judic->id_etat_infra = $request->id_etat_infra;

        if ($Judic->save())
            return response()->json( $Judic->toArray(), 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateEtatSourceOsc(Request $request, $id) {
        try {
            $Judicdb = EtatSourceOsc::where('id', $request->id)->first();
            $Judic = array();
            $Judic['id_osc'] = is_null($request->id_osc) ? $Judicdb->id_osc : $request->id_osc;
            $Judic['id_etat_infra'] = is_null($request->id_etat_infra) ? $Judicdb->id_etat_infra : $request->id_etat_infra;
            $Judic['admin_id'] = $request->admin_id;
            $Judic['super_id'] = $request->super_id;
            $updated = DB::table('etat_oscs')->where('id', $request->id)->update($Judic);
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


    public function deleteEtatSourceOsc($id) {
        try {
            $Judicdb = EtatSourceOsc::where('id', $id)->get();
            if($Judicdb) {
                EtatSourceOsc::where('id', $id)->delete();
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
