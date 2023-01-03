<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Realisation;
use Illuminate\Http\Request;

class RealisationController extends Controller
{
    public function realisation()
    {
        $real = DB::table('realisations')
            ->get();
        return response()->json($real);
    }

    public function findRealisation($id)
    {
        try{
            $realis = DB::table('realisations')
                ->join('admins','realisations.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'realisations.*')
                ->where(['realisations.id' => $id])
                ->get();
            if (!$realis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $realis
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeRealis(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'description' => 'required',

        ]);

        $realisation = new Realisation();
        $realisation->admin_id = $request->admin_id;
        $realisation->super_id = $request->super_id;
        $realisation->nombre = $request->nombre;
        $realisation->description = $request->description;
        $realisation->status = 'initial';

        if ($realisation->save())
            return response()->json([
                'success' => true,
                'text' => $realisation->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updaterealis(Request $request, $id) {
        try {
            $realisdb = Realisation::where('id', $request->id)->first();
            $realisaton = array();
            $realisaton['nombre'] = is_null($request->nombre) ? $realisdb->nombre : $request->nombre;
            $realisaton['description'] = is_null($request->description) ? $realisdb->description : $request->description;
            $realisaton['status'] = is_null($request->status) ? $realisdb->status : $request->status;
            $realisaton['admin_id'] = $request->admin_id;
            $realisaton['super_id'] = $request->super_id;
            $updated = DB::table('realisations')->where('id', $request->id)->update($realisaton);
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


    public function deleteRealisa($id) {
        try {
            $realisadb = Realisation::where('id', $id)->get();
            if($realisadb) {
                Realisation::where('id', $id)->delete();
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

