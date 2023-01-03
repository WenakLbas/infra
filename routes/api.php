<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FSIController;
use App\Http\Controllers\OSCController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EnqueteController;
use App\Http\Controllers\VictimeController;
use App\Http\Controllers\Slider01Controller;
use App\Http\Controllers\Slider02Controller;
use App\Http\Controllers\Slider03Controller;
use App\Http\Controllers\Slider04Controller;
use App\Http\Controllers\Slider05Controller;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\LieuInfraController;
use App\Http\Controllers\GalleriesController;
use App\Http\Controllers\PartnerController01;
use App\Http\Controllers\Partner02Controller;
use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\TypeMediaController;
use App\Http\Controllers\EtatEnqteController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\InfractionController;
use App\Http\Controllers\categorieprController;
use App\Http\Controllers\RaisnSatisfController;
use App\Http\Controllers\RaisnNnEnqtController;
use App\Http\Controllers\TrancheAgeController;
use App\Http\Controllers\TypeEnqueteController;
use App\Http\Controllers\SourceMediaController;
use App\Http\Controllers\RealisationController;
use App\Http\Controllers\EtatNonEnqteController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\TeamsProjectController;
use App\Http\Controllers\EtatSourceOscController;
use App\Http\Controllers\EtatGardeAvueController;
use App\Http\Controllers\DureGardeAvueController;
use App\Http\Controllers\DecisionFinalController;
use App\Http\Controllers\EtatInfraJourController;
use App\Http\Controllers\TypeInfractionController;
use App\Http\Controllers\AutoriteSaisieController;
use App\Http\Controllers\typeProffessionController;
use App\Http\Controllers\EtatMediaSourceController;
use App\Http\Controllers\EtatSatisfactionController;
use App\Http\Controllers\SourceJudiciaireController;
use App\Http\Controllers\EtatDecisionFinalController;
use App\Http\Controllers\EtatSourceJudiciaireController;
use App\Http\Controllers\EtatAutoriteDenonciationController;


Route::delete('user_del/{id}', [UserController::class, 'deleteUser']);
Route::get('get_user/{id}', [UserController::class, 'getUser']);
Route::PUT('user_update/{id}', [UserController::class, 'updateUser']);

Route::post('super_admin', [SuperAdminController::class, 'login']);
Route::post('super_admin_reg', [SuperAdminController::class, 'register']);

Route::post('admin', [AdminController::class, 'adminLogin']);
Route::post('admin_register', [AdminController::class, 'adminRegister']);

Route::get('cnt_infras', [InfractionController::class, 'countInfras']);
Route::get('cnt_infrvol', [InfractionController::class, 'countInfrasViol']);
Route::get('cnt_infrdeon', [InfractionController::class, 'countInfrasDeon']);

Route::get('all_infractions',  [InfractionController::class, 'allInfractions']);
Route::get('infraction/{id}',  [InfractionController::class, 'infraction']);

Route::get('actualites',  [ActualiteController::class, 'actualites']);
Route::get('actualite/{id}',  [ActualiteController::class, 'findActualite']);

Route::get('activites',  [ActiviteController::class, 'activites']);
Route::get('activite/{id}',  [ActiviteController::class, 'findActivite']);

Route::get('enquetes',  [EnqueteController::class, 'enquetes']);
Route::get('enquete/{id}',  [EnqueteController::class, 'findEnquete']);

Route::get('oscs',  [OSCController::class, 'typeOSCs']);
Route::get('osc/{id}',  [OSCController::class, 'findOSC']);

Route::get('source_medias',  [SourceMediaController::class, 'sourceMedias']);
Route::get('source_media/{id}',  [SourceMediaController::class, 'findSourceMedia']);

Route::get('realisations',  [RealisationController::class, 'realisation']);
Route::get('realisation/{id}',[RealisationController::class, 'findRealisation']);

Route::post('partenaire',  [PartnerController01::class, 'storePartenaire']);
Route::get('partenaires',  [PartnerController01::class, 'partenaires']);
Route::get('partenaire',  [PartnerController01::class, 'onePrtenaires']);
Route::get('partenaire/{id}',[PartnerController01::class, 'findPartenaire']);

Route::get('partner02s',  [Partner02Controller::class, 'partner02s']);
Route::get('partner02/{id}',[Partner02Controller::class, 'findPartner02']);

Route::get('teams',  [TeamsController::class, 'teams']);
Route::get('team/{id}',[TeamsController::class, 'findTeam']);

Route::get('team_projects',  [TeamsProjectController::class, 'teams']);
Route::get('team_project/{id}',[TeamsProjectController::class, 'findTeam']);

Route::get('galleries',  [GalleriesController::class, 'galleries']);
Route::get('gallerie/{id}',[GalleriesController::class, 'findGallerie']);

Route::get('categories',  [categorieprController::class, 'categories']);
Route::get('categorie/{id}',  [categorieprController::class, 'findcategories']);

Route::get('provinces',  [ProvinceController::class, 'provinces']);
Route::get('province/{id}',  [ProvinceController::class, 'findprovinces']);

Route::get('victimes',  [VictimeController::class, 'victimes']);
Route::get('victime/{id}',  [VictimeController::class, 'findVictime']);

Route::get('autorite_saisies',  [AutoriteSaisieController::class, 'autoriteSaisies']);
Route::get('autorite_saisie/{id}',  [AutoriteSaisieController::class, 'findAutoriteSaisie']);

Route::get('decision_finals',  [DecisionFinalController::class, 'decisionfinals']);
Route::get('decision_final/{id}',  [DecisionFinalController::class, 'findDecisionFinal']);

Route::get('denonciations',  [EtatAutoriteDenonciationController::class, 'decisionfinals']);
Route::get('denonciation/{id}',  [EtatAutoriteDenonciationController::class, 'findDecisionFinal']);

Route::get('gerdeavues',  [DureGardeAvueController::class, 'gerdeavues']);
Route::get('gerdeavue/{id}',  [DureGardeAvueController::class, 'findDureGardeAvue']);

Route::get('rsnonenqtes',  [RaisnNnEnqtController::class, 'rsnonenqtes']);
Route::get('rsnonenqte/{id}',  [RaisnNnEnqtController::class, 'findRaisnNmEnqte']);

Route::get('rs_satisfactions',  [RaisnSatisfController::class, 'raisonsatisfactions']);
Route::get('rs_satisfaction/{id}',  [RaisnSatisfController::class, 'findRaisnSatisf']);

Route::get('tranche_ages',  [TrancheAgeController::class, 'trancheAges']);
Route::get('tranche_age/{id}',  [TrancheAgeController::class, 'findTrancheAge']);

Route::get('type_enquetes',  [TypeEnqueteController::class, 'typeEnquetes']);
Route::get('type_enquete/{id}',  [TypeEnqueteController::class, 'findtEnquetes']);

Route::get('etat_infras',  [EtatInfraJourController::class, 'etat_infrjrs']);
Route::get('etat_infracts',  [EtatInfraJourController::class, 'etatInfras']);
Route::get('etat_infrascount',  [EtatInfraJourController::class, 'etatInfrCount']);
Route::get('etat_infrascount_4',  [EtatInfraJourController::class, 'etatInfrCount_3']);
Route::get('etat_infract/{id}',  [EtatInfraJourController::class, 'etatInfra']);
Route::get('etat_infra/{id}',  [EtatInfraJourController::class, 'findautorite']);

Route::get('infras_count',  [EtatInfraJourController::class, 'infraCount']);
Route::get('infras_count_4',  [EtatInfraJourController::class, 'infraCount_4']);

Route::get('etat_denonciations',  [EtatAutoriteDenonciationController::class, 'denonciations']);
Route::get('etat_denonciation/{id}',  [EtatAutoriteDenonciationController::class, 'findEtatDenonciation']);

Route::get('etat_decisions',  [EtatDecisionFinalController::class, 'decisions']);
Route::get('etat_decision/{id}',  [EtatDecisionFinalController::class, 'findEtatDecision']);

Route::get('etat_enqtes',  [EtatEnqteController::class, 'etatEnqtes']);
Route::get('etat_enqte/{id}',  [EtatEnqteController::class, 'findEtatEnqte']);

Route::get('etat_gardeavues',  [EtatGardeAvueController::class, 'etatNonEnqtes']);
Route::get('etat_gardeavue/{id}',  [EtatGardeAvueController::class, 'findEtatNonEnqte']);

Route::get('etat_nonenqtes',  [EtatNonEnqteController::class, 'etatNonEnqtes']);
Route::get('etat_nonenqte/{id}',  [EtatNonEnqteController::class, 'findEtatNonEnqte']);

Route::get('etat_smedias',  [EtatMediaSourceController::class, 'etatMediaSources']);
Route::get('etat_smedia/{id}',  [EtatMediaSourceController::class, 'findEtatMediaSource']);

Route::get('etat_satisfactions',  [EtatSatisfactionController::class, 'etatSatisfactions']);
Route::get('etat_satisfaction/{id}',  [EtatSatisfactionController::class, 'findEtatSatisfaction']);

Route::get('etat_judiciaires',  [EtatSourceJudiciaireController::class, 'etatJudiciaires']);
Route::get('etat_judiciaire/{id}',  [EtatSourceJudiciaireController::class, 'findEtatJudiciaire']);

Route::get('etat_oscs',  [EtatSourceOscController::class, 'etatSourceOscs']);
Route::get('etat_osc/{id}',  [EtatSourceOscController::class, 'findEtatSourceOsc']);

Route::get('presentations',  [PresentationController::class, 'presentations']);
Route::get('presentation/{id}',  [PresentationController::class, 'findPresentation']);
Route::get('presentation',  [PresentationController::class, 'onePresentation']);

Route::get('latest_slider01',  [Slider01Controller::class, 'latestLider01s']);

Route::get('slider01s',  [Slider01Controller::class, 'slider01s']);
Route::get('slider02s',  [Slider02Controller::class, 'slider02s']);

Route::group([
    'middleware' => 'auth:users'
],function ()
{
    /* Route::get('users', [UserController::class, 'users']);
   Route::get('logout', [SuperAdminController::class, 'logout']);
   Route::delete('user_delete/{id}', [UserController::class, 'deleteUser']);*/
    Route::get('user/{id}', [UserController::class, 'getUser']);
    Route::PUT('update_user/{id}', [UserController::class, 'updateUser']);
    Route::get('me_user', [UserController::class, 'getAuthenticatedUser']);

    Route::get('user_refresh', [UserController::class, 'refresh']);
    Route::get('user_logout',  [UserController::class, 'logout']);
    Route::put('change_pwd',  [UserController::class, 'changePwd']);
});

Route::group([
    'middleware' => 'auth:admins'
],function ()
{

    Route::get('me_admin', [AdminController::class, 'getAuthenticatedAdmin']);
    Route::get('admins', [AdminController::class, 'admins']);
    Route::get('admin/{id}', [AdminController::class, 'getAdmin']);
    Route::post('admin/{id}', [AdminController::class, 'updateAdmin']);
    Route::put('admin_password/{id}', [AdminController::class, 'changePwd']);
    Route::delete('admin/{id}', [AdminController::class, 'deleteAdmin']);
    Route::get('logout', [AdminController::class, 'logout']);

    Route::get('type_infras',  [TypeInfractionController::class, 'type_infras']);
    Route::get('type_infractions',  [TypeInfractionController::class, 'type_infractions']);
    Route::post('type_infra',  [TypeInfractionController::class, 'storeTypeInfra']);
    Route::get('type_infra/{id}',  [TypeInfractionController::class, 'type_infra']);
    Route::put('type_infra/{id}',  [TypeInfractionController::class, 'updateInfraction']);
    Route::delete('type_infra/{id}',  [TypeInfractionController::class, 'deleteInfraction']);

    Route::get('infractions',  [InfractionController::class, 'infractions']);
    Route::post('infraction',  [InfractionController::class, 'storeInfra']);
    Route::put('infraction/{id}',  [InfractionController::class, 'updateInfra']);
    Route::delete('infraction/{id}',  [InfractionController::class, 'deleteInfra']);

    Route::get('reports',  [ReportController::class, 'reports']);
    Route::post('report',  [ReportController::class, 'storeReport']);
    Route::get('report/{id}',  [ReportController::class, 'findReport']);
    Route::post('report/{id}',  [ReportController::class, 'updateReport']);
    Route::delete('report/{id}',  [ReportController::class, 'deleteReport']);

    Route::get('source_judiciaires',  [SourceJudiciaireController::class, 'sourceJudiciaires']);
    Route::post('source_judiciaire',  [SourceJudiciaireController::class, 'storeSourceJudiciaire']);
    Route::get('source_judiciaire/{id}',  [SourceJudiciaireController::class, 'findSourceJudiciaire']);
    Route::put('source_judiciaire/{id}',  [SourceJudiciaireController::class, 'updateSourceJudiciaire']);
    Route::delete('source_judiciaire/{id}',  [SourceJudiciaireController::class, 'deleteSourceJudiciaire']);

    Route::get('fsis',  [FSIController::class, 'fsis']);
    Route::post('fsi',  [FSIController::class, 'storeFSI']);
    Route::get('fsi/{id}',  [FSIController::class, 'findFSI']);
    Route::put('fsi/{id}',  [FSIController::class, 'updateFSI']);
    Route::delete('fsi/{id}',  [FSIController::class, 'deleteFSI']);

    Route::get('type_medias',  [TypeMediaController::class, 'typeMedias']);
    Route::post('type_media',  [TypeMediaController::class, 'storeTypeMedia']);
    Route::get('type_media/{id}',  [TypeMediaController::class, 'findTypeMedia']);
    Route::put('type_media/{id}',  [TypeMediaController::class, 'updateTypeMedia']);
    Route::delete('type_media/{id}',  [TypeMediaController::class, 'deleteTypeMedia']);

    Route::post('source_media',  [SourceMediaController::class, 'storeSourceMedia']);
    Route::put('source_media/{id}',  [SourceMediaController::class, 'updateSourceMedia']);
    Route::delete('source_media/{id}',  [SourceMediaController::class, 'deleteSourceMedia']);


    Route::post('osc',  [OSCController::class, 'storeOSC']);
    Route::put('osc/{id}',  [OSCController::class, 'updateOSC']);
    Route::delete('osc/{id}',  [OSCController::class, 'deleteOSC']);

    Route::get('lieu_infras',  [LieuInfraController::class, 'lieuInfras']);
    Route::post('lieu_infra',  [LieuInfraController::class, 'storeLieuInfra']);
    Route::get('lieu_infra/{id}',  [LieuInfraController::class, 'findLieuInfra']);
    Route::put('lieu_infra/{id}',  [LieuInfraController::class, 'updateLieuInfra']);
    Route::delete('lieu_infra/{id}',  [LieuInfraController::class, 'deleteLieuInfra']);

    Route::post('type_enquete',  [TypeEnqueteController::class, 'storeTypendt']);
    Route::put('type_enquete/{id}',  [TypeEnqueteController::class, 'updateTypenqt']);
    Route::delete('type_enquete/{id}',  [TypeEnqueteController::class, 'deleteTypenqt']);

    Route::post('province',  [ProvinceController::class, 'storeProvince']);
    Route::put('province/{id}',  [ProvinceController::class, 'updateProvince']);
    Route::delete('province/{id}',  [ProvinceController::class, 'deleteProvince']);

    Route::post('categorie',  [categorieprController::class, 'storecategorie']);
    Route::put('categorie/{id}',  [categorieprController::class, 'updatecategorie']);
    Route::delete('categorie/{id}',  [categorieprController::class, 'deletecategorie']);

    Route::get('typeprofs',  [typeProffessionController::class, 'typeProfessions']);
    Route::post('typeprof',  [typeProffessionController::class, 'storetProfessions']);
    Route::get('typeprof/{id}',  [typeProffessionController::class, 'findtProffessions']);
    Route::put('typeprof/{id}',  [typeProffessionController::class, 'updatetProfession']);
    Route::delete('typeprof/{id}',  [typeProffessionController::class, 'deleteTypepro']);

    Route::post('actualite',  [ActualiteController::class, 'storeActualite']);
    Route::post('actualite/{id}',  [ActualiteController::class, 'updateActualite']);
    Route::delete('actualite/{id}',  [ActualiteController::class, 'deleteActualite']);

    Route::post('activite',  [ActiviteController::class, 'storeActivite']);
    Route::put('activite/{id}',  [ActiviteController::class, 'updateActivite']);
    Route::delete('activite/{id}',  [ActiviteController::class, 'deleteActivite']);

    Route::post('enquete',  [EnqueteController::class, 'storEnquete']);
    Route::post('enquete/{id}',  [EnqueteController::class, 'updateEnquete']);
    Route::delete('enquete/{id}',  [EnqueteController::class, 'deleteEnquete']);

    Route::post('presentation',  [PresentationController::class, 'storePresentation']);
    Route::post('presentation/{id}',  [PresentationController::class, 'updatePresentation']);
    Route::delete('presentation/{id}',  [PresentationController::class, 'deletePresentation']);

    Route::post('realisation',  [RealisationController::class, 'storeRealis']);
    Route::put('realisation/{id}', [RealisationController::class, 'updaterealis']);
    Route::delete('realisation/{id}',  [RealisationController::class, 'deleteRealisa']);

    Route::post('partenaire',  [PartnerController01::class, 'storePartenaire']);
    Route::post('partenaire/{id}', [PartnerController01::class, 'updatePartenaire']);
    Route::delete('partenaire/{id}',  [PartnerController01::class, 'deletePartenaire']);


    Route::post('team',  [TeamsController::class, 'storeTeam']);
    Route::post('team/{id}', [TeamsController::class, 'updateTeam']);
    Route::delete('team/{id}',  [TeamsController::class, 'deleteTeam']);

    Route::post('team_project',  [TeamsProjectController::class, 'storeTeam']);
    Route::post('team_project/{id}', [TeamsProjectController::class, 'updateTeam']);
    Route::delete('team_project/{id}',  [TeamsProjectController::class, 'deleteTeam']);

    Route::post('partner02',  [Partner02Controller::class, 'storePartner02']);
    Route::post('partner02/{id}', [Partner02Controller::class, 'updatePartner02']);
    Route::delete('partner02/{id}',  [Partner02Controller::class, 'deletePartner02']);

    Route::post('gallerie',  [GalleriesController::class, 'storeGallerie']);
    Route::post('gallerie/{id}', [GalleriesController::class, 'updateGallerie']);
    Route::delete('gallerie/{id}',  [GalleriesController::class, 'deleteGallerie']);

    Route::post('victime',  [VictimeController::class, 'saveVictime']);
    Route::post('victime/{id}', [VictimeController::class, 'updateVictime']);
    Route::delete('victime/{id}',  [VictimeController::class, 'deleteVictime']);

    Route::post('decision_final',  [DecisionFinalController::class, 'storeDecisionFinal']);
    Route::post('decision_final/{id}', [DecisionFinalController::class, 'updateDecisionFinal']);
    Route::delete('decision_final/{id}',  [DecisionFinalController::class, 'deleteDecisionFinal']);

    Route::post('autorite_saisie',  [AutoriteSaisieController::class, 'storeAutoriteSaisie']);
    Route::put('autorite_saisie/{id}', [AutoriteSaisieController::class, 'updateAutoriteSaisie']);
    Route::delete('autorite_saisie/{id}',  [AutoriteSaisieController::class, 'deleteAutoriteSaisie']);

    Route::post('gerdeavue',  [DureGardeAvueController::class, 'storeDureGardeAvue']);
    Route::put('gerdeavue/{id}', [DureGardeAvueController::class, 'updateDureGardeAvue']);
    Route::delete('gerdeavue/{id}',  [DureGardeAvueController::class, 'deleteDureGardeAvue']);

    Route::post('rsnonenqte',  [RaisnNnEnqtController::class, 'storeRaisNnEnqt']);
    Route::put('rsnonenqte/{id}', [RaisnNnEnqtController::class, 'updateraisnEnqte']);
    Route::delete('rsnonenqte/{id}',  [RaisnNnEnqtController::class, 'deleteRaisnEnqte']);

    Route::post('rs_satisfaction',  [RaisnSatisfController::class, 'storeRaisnSatisf']);
    Route::put('rs_satisfaction/{id}', [RaisnSatisfController::class, 'updateraisnSatisf']);
    Route::delete('rs_satisfaction/{id}',  [RaisnSatisfController::class, 'deleteRaisnSatisf']);

    Route::post('tranche_age',  [TrancheAgeController::class, 'storeTrancheAge']);
    Route::put('tranche_age/{id}', [TrancheAgeController::class, 'updateTrancheAge']);
    Route::delete('tranche_age/{id}',  [TrancheAgeController::class, 'deleteTrancheAge']);

    Route::post('etat_infra',  [EtatInfraJourController::class, 'storeEtatInfraJrs']);
    Route::put('etat_infra/{id}', [EtatInfraJourController::class, 'updateEtatInfraJrs']);
    Route::delete('etat_infra/{id}',  [EtatInfraJourController::class, 'deleteEtatInfraJrs']);

    Route::post('etat_denonciation',  [EtatAutoriteDenonciationController::class, 'storeEtatDenonciation']);
    Route::put('etat_denonciation/{id}', [EtatAutoriteDenonciationController::class, 'updateEtatDenonciation']);
    Route::delete('etat_denonciation/{id}',  [EtatAutoriteDenonciationController::class, 'deleteEtatDenonciation']);

    Route::post('etat_decision',  [EtatDecisionFinalController::class, 'storeEtatDecision']);
    Route::put('etat_decision/{id}', [EtatDecisionFinalController::class, 'updateEtatDecision']);
    Route::delete('etat_decision/{id}',  [EtatDecisionFinalController::class, 'deleteEtatDecision']);

    Route::post('etat_enqte',  [EtatEnqteController::class, 'storeEtatEnqte']);
    Route::put('etat_enqte/{id}', [EtatEnqteController::class, 'updateEtatEnqte']);
    Route::delete('etat_enqte/{id}',  [EtatEnqteController::class, 'deleteEtatEnqte']);

    Route::post('etat_gardeavue',  [EtatGardeAvueController::class, 'storeEtatGardeAvue']);
    Route::put('etat_gardeavue/{id}', [EtatGardeAvueController::class, 'updateEtatGardeAvue']);
    Route::delete('etat_gardeavue/{id}',  [EtatGardeAvueController::class, 'deleteEtatGardeAvue']);

    Route::post('etat_nonenqte',  [EtatNonEnqteController::class, 'storeEtatNonEnqte']);
    Route::put('etat_nonenqte/{id}', [EtatNonEnqteController::class, 'updateEtatNonEnqte']);
    Route::delete('etat_nonenqte/{id}',  [EtatNonEnqteController::class, 'deleteEtatNonEnqte']);

    Route::post('etat_smedia',  [EtatMediaSourceController::class, 'storeEtatMediaSource']);
    Route::put('etat_smedia/{id}', [EtatMediaSourceController::class, 'updateEtatMediaSource']);
    Route::delete('etat_smedia/{id}',  [EtatMediaSourceController::class, 'deleteEtatMediaSource']);

    Route::post('etat_satisfaction',  [EtatSatisfactionController::class, 'storeEtatSatisfaction']);
    Route::put('etat_satisfaction/{id}', [EtatSatisfactionController::class, 'updateEtatSatisfaction']);
    Route::delete('etat_satisfaction/{id}',  [EtatSatisfactionController::class, 'deleteEtatSatisfaction']);

    Route::post('etat_judiciaire',  [EtatSourceJudiciaireController::class, 'storeEtatJudiciaire']);
    Route::put('etat_judiciaire/{id}', [EtatSourceJudiciaireController::class, 'updateEtatJudiciaire']);
    Route::delete('etat_judiciaire/{id}',  [EtatSourceJudiciaireController::class, 'deleteEtatJudiciaire']);

    Route::post('etat_osc',  [EtatSourceOscController::class, 'storeEtatSourceOsc']);
    Route::put('etat_osc/{id}', [EtatSourceOscController::class, 'updateEtatSourceOsc']);
    Route::delete('etat_osc/{id}',  [EtatSourceOscController::class, 'deleteEtatSourceOsc']);

    Route::post('slider01',  [Slider01Controller::class, 'saveSlider']);
    Route::get('slider01/{id}',  [Slider01Controller::class, 'findSlider01']);
    Route::post('slider01/{id}',  [Slider01Controller::class, 'updateSlider01']);
    Route::delete('slider01/{id}',  [Slider01Controller::class, 'deleteSlider01']);

    Route::post('slider02',  [Slider02Controller::class, 'saveSlider02']);
    Route::get('slider02/{id}',  [Slider02Controller::class, 'findSlider02']);
    Route::post('slider02/{id}',  [Slider02Controller::class, 'updateSlider02']);
    Route::delete('slider02/{id}',  [Slider02Controller::class, 'deleteSlider02']);

});

