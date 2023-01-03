<?php

namespace App\Http\Controllers;

use DB;
use App\Models\RaisonNonEnquete;

use Illuminate\Http\Request;

class RaisnNnEnqtController extends Controller
{
    public function rsnonenqtes()
    {
        $raisneqnt = DB::table('rsnonenqtes')
            ->get();
        return response()->json($raisneqnt);
    }

    public function findRaisnNmEnqte($id)
    {
        try{
            $raisnqt = DB::table('rsnonenqtes')
                ->join('admins','rsnonenqtes.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'rsnonenqtes.*')
                ->where(['rsnonenqtes.id' => $id])
                ->get();
            if (!$raisnqt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $raisnqt
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeRaisNnEnqt(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',


        ]);

        $raisonenqte = new RaisonNonEnquete();
        $raisonenqte->admin_id = $request->admin_id;
        $raisonenqte->super_id = $request->super_id;
        $raisonenqte->libelle = $request->libelle;


        if ($raisonenqte->save())
            return response()->json([
                'success' => true,
                'text' => $raisonenqte->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateraisnEnqte(Request $request, $id) {
        try {
            $raisnqtedb = RaisonNonEnquete::where('id', $request->id)->first();
            $raisnqte = array();
            $raisnqte['libelle'] = is_null($request->libelle) ? $raisnqtedb->libelle : $request->libelle;
            $raisnqte['admin_id'] = $request->admin_id;
            $raisnqte['super_id'] = $request->super_id;
            $updated = DB::table('rsnonenqtes')->where('id', $request->id)->update($raisnqte);
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


    public function deleteRaisnEnqte($id) {
        try {
            $raisnEnqtdb = RaisonNonEnquete::where('id', $id)->get();
            if($raisnEnqtdb) {
                RaisonNonEnquete::where('id', $id)->delete();
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

