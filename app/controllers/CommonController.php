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
            return Redirect::to("http://localhost/makeadiff.in/home/makeadiff/public_html/madapp/index.php/auth/logout");
        } else {
            return Redirect::to("http://makeadiff.in/madapp/index.php/auth/logout");
        }

    }

}