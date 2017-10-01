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
  //  $_SESSION['user_id'] = 57184;

    if(empty($_SESSION['user_id'])){

        if(App::environment('local'))
            return Redirect::to('http://localhost/makeadiff.in/home/makeadiff/public_html/madapp/index.php/auth/login/' . base64_encode(Request::url()));
        else
            return Redirect::to('http://makeadiff.in/madapp/index.php/auth/login/' . base64_encode(Request::url()));

    }


});

Route::filter('reimbursement_active',function()
    {
        return Redirect::to('/error')->with('message','Reimbursements are currently being processed nationally. You can apply for reimbursements once it has been closed.');

    });


Route::group(array('before'=>'login_check'),function()
{

    // Route::get('/telephone-internet',array('uses' => 'Reimbursement@showTelephoneInternet')); //Uncomment this line and comment the line below to enable reimbursements
    Route::get('/telephone-internet',array('uses' => 'Reimbursement@showTelephoneInternet', 'before' => 'reimbursement_active')); //Uncomment this line and comment the line above to disable reimbursements


    Route::get('/', 'Reimbursement@showHome');
    Route::get('/travel','Reimbursement@showTravel');
    Route::get('/success','Reimbursement@showSuccess');
    Route::get('/error','Reimbursement@showError');
    Route::get('/status','Reimbursement@showStatus');
    Route::post('/submit/telephone-internet','Reimbursement@submitTelephoneInternet');
    Route::post('/submit/travel','Reimbursement@submitTravel');
    Route::get('/logout','CommonController@logout');
    // Route::get('/admin','CommonController@admin')
});
