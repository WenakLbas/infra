<?php

namespace App\Http\Controllers;

use DB;
use App\Models\FSI;
use Illuminate\Http\Request;

class FSIController extends Controller
{
    public function fsis()
    {
        $fsis = DB::table('fsis')
            ->get();
        return response()->json($fsis, 200);
    }


    public function findFSI($id)
    {
        try{
            $fsi = DB::table('fsis')
                ->join('admins','fsis.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'fsis.*')
                ->where(['fsis.id' => $id])
                ->get();
            if (!$fsi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $fsi
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeFSI(Request $request)
    {
        $this->validate($request, [
            'struct_securitaire' => 'required',
            'type_post' => 'required',
            'fonction_fsi' => 'required',
        ]);

        $fsi = new FSI();
        $fsi->admin_id = $request->admin_id;
        $fsi->super_id = $request->super_id;
        $fsi->struct_securitaire = $request->struct_securitaire;
        $fsi->type_post = $request->type_post;
        $fsi->fonction_fsi = $request->fonction_fsi;

        if ($fsi->save())
            return response()->json([
                'success' => true,
                'text' => $fsi->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateFSI(Request $request, $id) {
        try {
            $fsid = FSI::where('id', $id)->first();
            $fsi = array();
            $fsi['struct_securitaire'] = is_null($request->struct_securitaire) ? $fsid->struct_securitaire : $request->struct_securitaire;
            $fsi['type_post'] = is_null($request->type_post) ? $fsid->type_post : $request->type_post;
            $fsi['fonction_fsi'] = is_null($request->fonction_fsi) ? $fsid->fonction_fsi : $request->fonction_fsi;
            $fsi['admin_id'] = $request->admin_id;
            $fsi['super_id'] = $request->super_id;
            $updated = DB::table('fsis')->where('id', $id)->update($fsi);
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


    public function deleteFSI($id) {
        try {
            $infra = FSI::where('id', $id)->get();
            if($infra) {
                FSI::where('id', $id)->delete();
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

