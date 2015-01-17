<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::filter('login_check',function()
{
   session_start();

   if(empty($_SESSION['user_id'])){
      return Redirect::to('http://makeadiff.in/madapp/index.php/auth/login/' . base64_encode(Request::url()));
      //return Redirect::to('http://localhost/makeadiff.in/home/makeadiff/public_html/apps/set_session_test.php?url=' . base64_encode(Request::url()));
   }


});

Route::filter('reimbursement_active',function()
    {
        return Redirect::to('/error')->with('message','Reimbursements are currently being processed nationally. You can apply for reimbursements once it has been closed.');

    });


Route::group(array('before'=>'login_check'),function()
{
    Route::get('/', 'Reimbursement@showHome');
    Route::get('/telephone-internet',array('uses' => 'Reimbursement@showTelephoneInternet','before' => 'reimbursement_active'));
    Route::get('/travel','Reimbursement@showTravel');
    Route::get('/success','Reimbursement@showSuccess');
    Route::get('/error','Reimbursement@showError');
    Route::get('/status','Reimbursement@showStatus');
    Route::post('/submit/telephone-internet','Reimbursement@submitTelephoneInternet');
    Route::post('/submit/travel','Reimbursement@submitTravel');
});


