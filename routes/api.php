<?php


use App\Http\Controllers\DisponibiliteContoller;
use App\Http\Controllers\JobberController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\RequestJobController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CategoriesController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
//Authentification
Route::post('/forget-password',[UserController::class,'forgetPassword']);
Route::group(['middleware'=>'api'],function($routes){
    Route::post('/register',[UserController::class,'register']);
    Route::post('/login',[UserController::class,'login']);
    Route::get('/logout',[UserController::class,'logout']);
    Route::get('/profile',[UserController::class,'profile']);
    Route::put('/profile-update',[UserController::class,'updateProfile']);
   // Route::get('/send-verify-mail//{email}',[UserController::class,'sendVerifyMail']);
    Route::get('/refresh-token',[UserController::class,'refreshToken']);   

});



//register prestataire
Route::post('jobbers', [JobberController::class,'store']);
 // get all jobbers 
Route::get('jobber', [JobberController::class,'index']);
 



//dashboard prestataire 
//get request and respond with offre
Route::post('requests/{id}/offers', [JobOfferController::class, 'create']);
Route::get('/provider-requests', [RequestJobController::class, 'getProviderRequests']);
//Disponibilité
Route::middleware('jwt.auth')->get('disponibilite', [DisponibiliteContoller::class, 'getUserAvailability']);
Route::middleware('jwt.auth')->post('disponibilites', [DisponibiliteContoller::class,'store']);
Route::get('disponibilites',  [DisponibiliteContoller::class,'index']);
Route::get('disponibilites/{id}',[DisponibiliteContoller::class,'show']);
Route::put('disponibilites/{id}', [DisponibiliteContoller::class,'update']);
Route::delete('disponibilites/{id}', [DisponibiliteContoller::class,'destroy']);

//dashboard client :
//poster et recevoir les demandes de services
Route::middleware('jwt.auth')->group(function () {
    Route::post('/post-to-jobber', [RequestJobController::class, 'postRequestToJobber']);
    Route::get('/client', [RequestJobController::class, 'getClientRequest']);
});

Route::post('job-requests', [RequestJobController::class, 'addrequestjob']);
Route::get('job-request',[RequestJobController::class,'getRequestJob']);
Route::put('job-requests/{id}',[RequestJobController::class,'updaterquestjob']);
Route::delete('job-requests/{id}',[RequestJobController::class,'deleteRequestJob']);

//gérer les avis
//le client post un avis au prestataire 
Route::post('/jobbers/{jobber_id}/reviews', [ReviewController::class, 'store']);
//get clientReview of jobber
Route::get('/jobbers/{id}/reviews', [ReviewController::class, 'getJobberReviews']);
//get alljobberReview
Route::get('review',[ReviewController::class,'getallreviews']);

//Gérer services dans dashboard prestataire
Route::middleware('jwt.auth')->group(function () {
Route::get('jobs', [JobController::class, 'getJob']);
Route::post('/job', [JobController::class, 'store']);
Route::put('/job/{job}', [JobController::class, 'update']);
Route::delete('/job/{job}', [JobController::class, 'destroy']);
});
//
Route::get('/job', [JobController::class, 'index']);
Route::get('/job/{job}', [JobController::class, 'show']);

//get service avec catégorie
Route::get('jobs/{id}', [JobController::class, 'get']);
Route::get('/categories', [CategoriesController::class, 'index']);
Route::get('/categories/{id}', [CategoriesController::class, 'show']);
Route::post('/categories', [CategoriesController::class, 'store']);
Route::put('/categories/{id}', [CategoriesController::class, 'update']);
Route::delete('/categories/{id}', [CategoriesController::class, 'destroy']);



//Route::group(['middleware' => ['jwt.verify']], function () {
    //job_request api 

// Endpoints pour les offres de prix avec middleware d'authentification JWT
/*Route::group(['prefix' => 'job-requests/{jobRequestId}'], function () {
    Route::post('/job-offers', [JobOfferController::class, 'store']);
    Route::put('/job-offers/{id}', [JobOfferController::class, 'update']);
    Route::delete('/job-offers/{id}', [JobOfferController::class, 'destroy']);
}.);*/
//})
/*
// get a single Prestataire
Route::get('prestataires/{id}', [JobberController::class,'show']);
// update a Prestataire
Route::put('prestataires/{id}', [JobberController::class,'update']);
// delete a Prestataire
Route::delete('prestataires/{id}', [JobberController::class,'destroy']);*/









