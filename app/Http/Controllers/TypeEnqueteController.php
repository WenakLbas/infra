<?php

namespace App\Http\Controllers;

use DB;
use App\Models\TypeEnquete;
use Illuminate\Http\Request;

class TypeEnqueteController extends Controller
{
    public function typeEnquetes()
    {
        $enquetes = DB::table('type_enquetes')->get()->all();
        return response()->json($enquetes, 200);
    }


    public function findtEnquetes($id)
    {
        try{
            $tEnquete = DB::table('type_enquetes')
                ->join('admins','type_enquetes.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'type_enquetes.*')
                ->where(['type_enquetes.id' => $id])
                ->get();
            if (!$tEnquete) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json( $tEnquete[0], 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeTypendt(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',
        ]);

        $typendt = new TypeEnquete();
        $typendt->admin_id = $request->admin_id;
        $typendt->super_id = $request->super_id;
        $typendt->libelle = $request->libelle;

        if ($typendt->save())
            return response()->json([
                'success' => true,
                'text' => $typendt->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateTypenqt(Request $request, $id) {
        try {
            $typenqdb = TypeEnquete::where('id', $id)->first();
            $typenqt = array();
            $typenqt['libelle'] = is_null($request->libelle) ? $typenqdb->libelle : $request->libelle;
            $typenqt['admin_id'] = $request->admin_id;
            $typenqt['super_id'] = $request->super_id;
            $updated = DB::table('type_enquetes')->where('id', $id)->update($typenqt);
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


    public function deleteTypenqt ($id) {
        try {
            $typenqdb = TypeEnquete::where('id', $id)->get();
            if($typenqdb) {
                TypeEnquete::where('id', $id)->delete();
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

