<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\DureGardeAvue;

class DureGardeAvueController extends Controller
{
    public function gerdeavues()
    {
        $decisions = DB::table('gerdeavues')->get();
        return response()->json($decisions);
    }


    public function findDureGardeAvue($id)
    {
        try{
            $garde = DB::table('gerdeavues')
                ->join('admins','gerdeavues.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'gerdeavues.*')
                ->where(['gerdeavues.id' => $id])
                ->get();
            if (!$garde) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $garde
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeDureGardeAvue(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',
        ]);

        $garde = new DureGardeAvue();
        $garde->admin_id = $request->admin_id;
        $garde->super_id = $request->super_id;
        $garde->libelle = $request->libelle;

        if ($garde->save())
            return response()->json([
                'success' => true,
                'text' => $garde->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateDureGardeAvue(Request $request, $id) {
        try {
            $grde = DureGardeAvue::where('id', $id)->first();
            $garde = array();
            $garde['libelle'] = is_null($request->libelle) ? $grde->libelle : $request->libelle;
            $garde['admin_id'] = $request->admin_id;
            $garde['super_id'] = $request->super_id;
            $updated = DB::table('gerdeavues')->where('id', $id)->update($garde);
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


    public function deleteDureGardeAvue($id) {
        try {
            $garde = DureGardeAvue::where('id', $id)->get();
            if($garde) {
                DureGardeAvue::where('id', $id)->delete();
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

