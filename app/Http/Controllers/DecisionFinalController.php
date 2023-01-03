<?php

namespace App\Http\Controllers;

use DB;
use App\Models\DecisionFinal;
use Illuminate\Http\Request;

class DecisionFinalController extends Controller
{
    public function decisionfinals()
    {
        $decisions = DB::table('decision_finals')
            ->get();
        return response()->json($decisions);
    }



    public function findDecisionFinal($id)
    {
        try{
            $decision = DB::table('decision_finals')
                ->join('admins','decision_finals.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'decision_finals.*')
                ->where(['decision_finals.id' => $id])
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

    public function storeDecisionFinal(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',
        ]);

        $decision = new DecisionFinal();
        $decision->admin_id = $request->admin_id;
        $decision->super_id = $request->super_id;
        $decision->libelle = $request->libelle;

        if ($decision->save())
            return response()->json([
                'success' => true,
                'text' => $decision->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateDecisionFinal(Request $request, $id) {
        try {
            $decisiondb = DecisionFinal::where('id', $id)->first();
            $decision = array();
            $decision['libelle'] = is_null($request->libelle) ? $decisiondb->libelle : $request->libelle;
            $decision['admin_id'] = $request->admin_id;
            $decision['super_id'] = $request->super_id;
            $updated = DB::table('decision_finals')->where('id', $id)->update($decision);
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


    public function deleteDecisionFinal($id) {
        try {
            $linfra = DecisionFinal::where('id', $id)->get();
            if($linfra) {
                DecisionFinal::where('id', $id)->delete();
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

