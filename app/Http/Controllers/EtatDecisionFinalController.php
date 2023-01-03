<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\EtatDecisionFinal;

class EtatDecisionFinalController extends Controller
{
    public function decisions()
    {
        $decisions = DB::table('etat_decisions')
            ->get();
        return response()->json($decisions, 200);
    }


    public function findEtatDecision($id)
    {
        try{
            $decision = DB::table('etat_decisions')
                ->join('admins','etat_decisions.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'etat_decisions.*')
                ->where(['etat_decisions.id' => $id])
                ->get();
            if (!$decision) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $decision
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeEtatDecision(Request $request)
    {
        $this->validate($request, [
            'id_decifinal' => 'required',
            'id_etat_infra' => 'required',
        ]);

        $decision = new EtatDecisionFinal();
        $decision->admin_id = $request->admin_id;
        $decision->super_id = $request->super_id;
        $decision->id_decifinal = $request->id_decifinal;
        $decision->id_etat_infra = $request->id_etat_infra;

        if ($decision->save())
            return response()->json($decision->toArray(), 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateEtatDecision(Request $request, $id) {
        try {
            $decisiondb = EtatDecisionFinal::where('id', $request->id)->first();
            $decision = array();
            $decision['id_decifinal'] = is_null($request->id_decifinal) ? $decisiondb->id_decifinal : $request->id_decifinal;
            $decision['id_etat_infra'] = is_null($request->id_etat_infra) ? $decisiondb->id_etat_infra : $request->id_etat_infra;
            $decision['admin_id'] = $request->admin_id;
            $decision['super_id'] = $request->super_id;
            $updated = DB::table('etat_decisions')->where('id', $request->id)->update($decision);
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


    public function deleteEtatDecision($id) {
        try {
            $decisiondb = EtatDecisionFinal::where('id', $id)->get();
            if($decisiondb) {
                EtatDecisionFinal::where('id', $id)->delete();
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
