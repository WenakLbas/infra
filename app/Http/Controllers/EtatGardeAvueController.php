<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\EtatGardeAvue;

class EtatGardeAvueController extends Controller
{
    public function gardeavues()
    {
        $gdeavues = DB::table('etat_gardeavues')
            ->get();
        return response()->json($gdeavues, 200);
    }


    public function findEtatGardeAvue($id)
    {
        try{
            $gdeavue = DB::table('etat_gardeavues')
                ->join('admins','etat_gardeavues.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'etat_gardeavues.*')
                ->where(['etat_gardeavues.id' => $id])
                ->get();
            if (!$gdeavue) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $gdeavue
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeEtatGardeAvue(Request $request)
    {
        $this->validate($request, [
            'id_gardeavue' => 'required',
            'id_etat_infra' => 'required',
        ]);

        $gdeavue = new EtatGardeAvue();
        $gdeavue->admin_id = $request->admin_id;
        $gdeavue->super_id = $request->super_id;
        $gdeavue->id_gardeavue = $request->id_gardeavue;
        $gdeavue->id_etat_infra = $request->id_etat_infra;

        if ($gdeavue->save())
            return response()->json($gdeavue->toArray(), 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateEtatGardeAvue(Request $request, $id) {
        try {
            $gdeavuedb = EtatGardeAvue::where('id', $request->id)->first();
            $gdeavue = array();
            $gdeavue['id_gardeavue'] = is_null($request->id_gardeavue) ? $gdeavuedb->id_gardeavue : $request->id_gardeavue;
            $gdeavue['id_etat_infra'] = is_null($request->id_etat_infra) ? $gdeavuedb->id_etat_infra : $request->id_etat_infra;
            $gdeavue['admin_id'] = $request->admin_id;
            $gdeavue['super_id'] = $request->super_id;
            $updated = DB::table('etat_gardeavues')->where('id', $request->id)->update($gdeavue);
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


    public function deleteEtatGardeAvue($id) {
        try {
            $gdeavuedb = EtatGardeAvue::where('id', $id)->get();
            if($gdeavuedb) {
                EtatGardeAvue::where('id', $id)->delete();
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
