<?php

namespace App\Http\Controllers;
use DB;
use App\Models\typeProfession;
use Illuminate\Http\Request;

class TypeProffessionController extends Controller
{
    public function typeProfessions()
    {
        $tprofesions = DB::table('type_professions')
            ->get();
        return response()->json([
            'success' => true,
            '$sinfras' => $tprofesions,
        ]);
    }


    public function findtProffessions($id)
    {
        try{
            $tEnquetes = DB::table('type_professions')
                ->join('admins','type_professions.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'type_professions.*')
                ->where(['type_professions.id' => $id])
                ->get();
            if (!$tEnquetes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $tEnquetes
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storetProfessions(Request $request)
    {
        $this->validate($request, [
            'libelle_type_pr' => 'required',
        ]);

        $typeprofession= new TypeProfession();
        $typeprofession->admin_id = $request->admin_id;
        $typeprofession->super_id = $request->super_id;
        $typeprofession->libelle_type_pr = $request->libelle_type_pr;

        if ($typeprofession->save())
            return response()->json([
                'success' => true,
                'text' => $typeprofession->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updatetProfession(Request $request, $id) {
        try {

            $professiondb = TypeProfession::where('id', $id)->first();

            $profession = array();
            $profession['libelle_type_pr'] = is_null($request->libelle_type_pr) ? $professiondb->libelle_type_pr : $request->libelle_type_pr;
            $profession['admin_id'] = $request->admin_id;
            $profession['super_id'] = $request->super_id;
            $updated = DB::table('type_professions')->where('id', $id)->update($profession);

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


    public function deleteTypepro ($id) {
        try {
            $typeprofdb = TypeProfession::where('id', $id)->get();
           // var_dump($typeprofdb);
            if($typeprofdb) {
                TypeProfession::where('id',$id)->delete();
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

