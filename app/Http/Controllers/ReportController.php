<?php

namespace App\Http\Controllers;

use DB;
use File;
use App\Models\Url;
use App\Models\Report;
use App\Models\Url_Path;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function reports()
    {
        $reports = DB::table('reports')
            ->join('admins','reports.admin_id','=','admins.id')
            ->join('infractions','reports.infra_id','=','infractions.id')
            ->select('admins.id as admin_id','admins.name',  'admins.email',
                'infractions.id as infra_id', 'infractions.libelle_inf',  'reports.*')
            ->get();
        return $reports->toArray();
    }

    public function findReport($id)
    {
        try{
            $report = DB::table('reports')
                ->join('admins','reports.admin_id','=','admins.id')
                ->join('infractions','reports.infra_id','=','infractions.id')
                ->select('admins.id as admin_id','admins.name',  'admins.email',
                    'infractions.id as infra_id', 'infractions.libelle_inf',  'reports.*')
                ->where(['reports.id' => $id])
                ->get();
            if (!$report) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(

                    $report[0]
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }


    public function storeReport(Request $request){
        $report = new Report;
        if($request->hasFile('link')){
            $doc = $request->file('link');
            $fileName = $request->file('link')->getClientOriginalName();
            $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
            $fileExtension = $request->file('link')->getClientOriginalExtension();
            $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
            $dest = public_path('/images/reports/');
            $doc->move($dest, $input['fileName']);

            $report->link = Url::URL_RAPPORT.$input['fileName'];
            $report->actus_id = $request->actus_id;
            $report->enq_id = $request->enq_id;
            $report->infra_id = $request->infra_id;
            $report->admin_id = $request->admin_id;
            $report->super_id = $request->super_id;

            if($report->link == null ){
                return response()->json([
                    'success' => false,
                    'message' => "Le Rapport n'a pas été soumis"
                ], 500);
            }else{
                try {
                    if ($report->save())
                        return response()->json([
                            'success' => true,
                            'message' => "Le Rapport est soumis avec successe."
                        ], 201);
                    else
                        return response()->json([
                            'success' => false,
                            'message' => "Le Rapport n'a pas été soumis"
                        ], 500);
                }catch (\Exception $e) {
                    return $e->getMessage();
                }
            }
        }
    }


    public function updateReport(Request $request) {
        try {
            $Reprt = Report::where('id', $request->id)->first();
            $report =  array();
            if($request->hasFile('link')){
                $doc = $request->file('link');
                $fileName = $request->file('link')->getClientOriginalName();
                $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
                $fileExtension = $request->file('link')->getClientOriginalExtension();
                $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
                $dest = public_path('/images/reports/');
                $doc->move($dest, $input['fileName']);
                // dd($input['fileName']);
                $report['link'] = Url::URL_RAPPORT.$input['fileName'];
                $report['infra_id'] = $request->infra_id;
                $report['actus_id'] = $request->actus_id;
                $report['enq_id'] = $request->enq_id;
                $report['super_id'] = $request->super_id;
                $report['admin_id'] = $request->admin_id;
                $this->deleteImage($Reprt->link);
                if ($report['link'] == null){
                    $report['link'] = $Reprt->link;
                }
                $updated = DB::table('reports')->where('id', $request->id)->update($report);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'Article a ete mis a jour avec successe...!"
                    ], 200);
                }
            }else{
                $report['infra_id'] = $request->infra_id;
                $report['actus_id'] = $request->actus_id;
                $report['enq_id'] = $request->enq_id;
                $report['super_id'] = $request->super_id;
                $report['admin_id'] = $request->admin_id;
                $this->deleteImage($Reprt->link);
                if ($report['link'] == null){
                    $report['link'] = $Reprt->link;
                }
                $updated = DB::table('reports')->where('id', $request->id)->update($report);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'Article a ete mis a jour avec successe...!"
                    ], 200);
                }
            }

        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                // "message" => 'Votre Profile ne peut pas etre mis a jour...!'
                "message" => $e->getMessage()
            ], 404);
        }
    }

    public function dbImgName($path){
        if($path !== Url_Path::URL.'reports/'){
            $name = is_string($path) ? explode('/', $path) : $path;
            $length = is_array($name) ? count($name) : 0;
            if($length !== 0){
                return $name[$length-1];
            }
        }else{
            return $name = null;
        }
    }

    public function deleteImage($path)
    {
        $imageName = $this->dbImgName($path);
        $image_path = 'images/reports/'.$imageName;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
    }

    public function deleteReport($id) {
        try {
            $report = Report::where('id', $id)->get();
            if($report) {
                Report::where('id', $id)->delete();
                $this->deleteImage($report[0]->link);
                return response()->json([
                    "message" => "L'enregistrement est supprimé définitivement"
                ], 202);
            } else {
                return response()->json([
                    "message" => 'Cet enregistrement n\'existe pas'
                    // "message" => $blog
                ], 404);
            }
        }catch (\Exception $e){
            return response()->json([
                "message" => "L'enregistrement ne peux pas etre supprimer"
                // 'message' => $e->getMessage()
            ], 404);
        }
    }
}
