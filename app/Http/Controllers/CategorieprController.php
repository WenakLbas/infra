<?php

namespace App\Http\Controllers;

use DB;
use App\Models\CategorieProfessionel;
use Illuminate\Http\Request;

class CategorieprController extends Controller
{
    public function categories()
    {
        $categories = DB::table('CategorieProfessionels')
            ->get();
        return response()->json($categories, 200);
    }


    public function findcategories($id)
    {
        try{
            $categories = DB::table('CategorieProfessionels')
                ->join('admins','CategorieProfessionels.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'CategorieProfessionels.*')
                ->where(['CategorieProfessionels.id' => $id])
                ->get();
            if (!$categories) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $categories
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storecategorie(Request $request)
    {
        $this->validate($request, [
            'libelle_cat' => 'required',
        ]);

        $categorie = new CategorieProfessionel();
        $categorie->admin_id = $request->admin_id;
        $categorie->super_id = $request->super_id;
        $categorie->libelle_cat = $request->libelle_cat;

        if ($categorie->save())
            return response()->json([
                'success' => true,
                'text' => $categorie->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updatecategorie(Request $request) {
        try {
            $categoriedb = CategorieProfessionel::where('id', $request->id)->first();
            $categorie = array();
            $categorie['libelle_cat'] = is_null($request->libelle_cat) ? $categoriedb->libelle_cat : $request->libelle_cat;
            $categorie['admin_id'] = $request->admin_id;
            $categorie['super_id'] = $request->super_id;
            $updated = DB::table('CategorieProfessionels')->where('id', $request->id)->update($categorie);
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


    public function deletecategorie ($id) {
        try {
            $categoriedb = CategorieProfessionel::where('id', $id)->get();
            if($categoriedb) {
                CategorieProfessionel::where('id', $id)->delete();
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
