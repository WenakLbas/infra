<?php

namespace App\Http\Controllers;

use DB;
use App\Models\LieuInfra;
use Illuminate\Http\Request;

class LieuInfraController extends Controller
{

    public function lieuInfras()
    {
        $reports = DB::table('lieu_infras')
            ->join('admins','lieu_infras.admin_id','=','admins.id')
            ->join('infractions','lieu_infras.infra_id','=','infractions.id')
            ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile',
                'infractions.id as infra_id', 'infractions.libelle_inf',   'lieu_infras.*')
            ->get();
        return $reports->toArray();
    }


    public function findLieuInfra($id)
    {
        try{
            $linfra = DB::table('lieu_infras')
                ->join('admins','lieu_infras.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'lieu_infras.*')
                ->where(['lieu_infras.id' => $id])
                ->get();
            if (!$linfra) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $linfra
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeLieuInfra(Request $request)
    {
        $this->validate($request, [
            'infra_id' => 'required',
            'libelle' => 'required',
        ]);

        $linfra = new LieuInfra();
        $linfra->admin_id = $request->admin_id;
        $linfra->super_id = $request->super_id;
        $linfra->infra_id = $request->infra_id;
        $linfra->libelle = $request->libelle;

        if ($linfra->save())
            return response()->json([
                'success' => true,
                'text' => $linfra->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateLieuInfra(Request $request, $id) {
        try {
            $linfrad = LieuInfra::where('id', $id)->first();
            $linfra = array();
            $linfra['libelle'] = is_null($request->libelle) ? $linfrad->libelle : $request->libelle;
            $linfra['infra_id'] = is_null($request->infra_id) ? $linfrad->infra_id : $request->infra_id;
            $linfra['admin_id'] = $request->admin_id;
            $linfra['super_id'] = $request->super_id;
            $updated = DB::table('lieu_infras')->where('id', $id)->update($linfra);
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


    public function deleteLieuInfra($id) {
        try {
            $linfra = LieuInfra::where('id', $id)->get();
            if($linfra) {
                LieuInfra::where('id', $id)->delete();
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

