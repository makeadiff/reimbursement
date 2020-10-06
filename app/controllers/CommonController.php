<?php

/**
 * Created by PhpStorm.
 * User: sanjay
 * Date: 16/7/15
 * Time: 11:43 AM
 */
class CommonController extends BaseController
{
    public function logout()
    {

        if(App::environment('local')) {
            return Redirect::to("http://localhost/MAD/apps/auth/logout.php");
        } else {
            return Redirect::to("https://makeadiff.in/apps/auth/logout.php");
        }

    }

}