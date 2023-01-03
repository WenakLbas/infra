<?php

namespace App\Http\Controllers;

use DB;
use App\Models\SourceJudiciaire;
use Illuminate\Http\Request;

class SourceJudiciaireController extends Controller
{
    public function sourceJudiciaires()
    {
        $sinfras = DB::table('source_judiciaires')
            ->get();
        return response()->json($sinfras, 200);
    }


    public function findSourceJudiciaire($id)
    {
        try{
            $sinfra = DB::table('source_judiciaires')
                ->join('admins','type_medias.admin_id','=','admins.id')
                ->join('superadmins','type_medias.super_id','=','superadmins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'source_judiciaires.*')
                ->where(['source_infras.id' => $id])
                ->get();
            if (!$sinfra) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $sinfra
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeSourceJudiciaire(Request $request)
    {
        $this->validate($request, [
            'fonction' => 'required',
        ]);

        $judiciaire = new SourceJudiciaire();
        $judiciaire->admin_id = $request->admin_id;
        $judiciaire->super_id = $request->super_id;
        $judiciaire->fonction = $request->fonction;

        if ($judiciaire->save())
            return response()->json([
                'success' => true,
                'text' => $judiciaire->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateSourceJudiciaire(Request $request, $id) {
        try {
            $judi = SourceJudiciaire::where('id', $id)->first();
            $judiciaire = array();
            $judiciaire['fonction'] = is_null($request->fonction) ? $judi->fonction : $request->fonction;
            $judiciaire['admin_id'] = $request->admin_id;
            $judiciaire['super_id'] = $request->super_id;
            $updated = DB::table('source_judiciaires')->where('id', $id)->update($judiciaire);
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


    public function deleteSourceJudiciaire($id) {
        try {
            $infra = SourceJudiciaire::where('id', $id)->get();
            if($infra) {
                SourceJudiciaire::where('id', $id)->delete();
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

