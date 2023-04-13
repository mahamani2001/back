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

/*gérer les avis*/

Route::middleware('jwt.auth')->get('reviews', [ReviewController::class,'getJobberReview']);
Route::post('review/{jobber_id}', [ReviewController::class,'store']);
Route::post('avis',[ReviewController::class,'addavis']);
//Route::post('avi',[ReviewController::class,'store']);
Route::put('avis/{id}',[ReviewController::class,'updateavis']);
Route::delete('avis/{id}',[ReviewController::class,'deleteavis']);
Route::get('avis',[ReviewController::class,'getallavis']);

// Tache prestataire 
 // get all Prestataires
Route::get('prestataire', [JobberController::class,'index']);
 // create a new Prestataire
Route::post('prestataires', [JobberController::class,'store']);
// get a single Prestataire
Route::get('prestataires/{id}', [JobberController::class,'show']);
// update a Prestataire
Route::put('prestataires/{id}', [JobberController::class,'update']);
// delete a Prestataire
Route::delete('prestataires/{id}', [JobberController::class,'destroy']);










Route::middleware('jwt.auth')->get('disponibilite', [DisponibiliteContoller::class, 'getUserAvailability']);
Route::get('disponibilites',  [DisponibiliteContoller::class,'index']);
Route::post('disponibilites', [DisponibiliteContoller::class,'store']);
Route::get('disponibilites/{id}',[DisponibiliteContoller::class,'show']);
Route::put('disponibilites/{id}', [DisponibiliteContoller::class,'update']);
Route::delete('disponibilites/{id}', [DisponibiliteContoller::class,'destroy']);



//Route::group(['middleware' => ['jwt.verify']], function () {
    //job_request api 

// Endpoints pour les offres de prix avec middleware d'authentification JWT
/*Route::group(['prefix' => 'job-requests/{jobRequestId}'], function () {
    Route::post('/job-offers', [JobOfferController::class, 'store']);
    Route::put('/job-offers/{id}', [JobOfferController::class, 'update']);
    Route::delete('/job-offers/{id}', [JobOfferController::class, 'destroy']);
}.);*/
//})
Route::get('job-request',[RequestJobController::class,'getRequestJob']);
Route::post('job-requests', [RequestJobController::class, 'addrequestjob']);
Route::put('job-requests/{id}',[RequestJobController::class,'updaterquestjob']);
Route::delete('job-requests/{id}',[RequestJobController::class,'deleteRequestJob']);
Route::get('search/{title}',[RequestJobController::class,'search']);
Route::post('requests/{id}/offers', [JobOfferController::class, 'create']);
//Route::get('/job-request', [RequestJobController::class, 'index']);
Route::post('/provider', [RequestJobController::class, 'postRequestToJobber']);
Route::get('/client-requests', [RequestJobController::class, 'getClientRequests']);
Route::get('/provider-requests', [YourController::class, 'getProviderRequests']);

//Tache admin
//Gérer services:
Route::middleware('jwt.auth')->get('jobs', [JobController::class, 'getJob']);

Route::get('/job', [JobController::class, 'index']);
Route::get('/job/{job}', [JobController::class, 'show']);
Route::post('/job', [JobController::class, 'store']);
Route::put('/job/{job}', [JobController::class, 'update']);
Route::delete('/job/{job}', [JobController::class, 'destroy']);


//get service avec catégorie
Route::get('jobs/{id}', [JobController::class, 'get']);
//Gérer Services 
Route::get('/categories', [CategoriesController::class, 'index']);
Route::get('/categories/{id}', [CategoriesController::class, 'show']);
Route::post('/categories', [CategoriesController::class, 'store']);
Route::put('/categories/{id}', [CategoriesController::class, 'update']);
Route::delete('/categories/{id}', [CategoriesController::class, 'destroy']);












