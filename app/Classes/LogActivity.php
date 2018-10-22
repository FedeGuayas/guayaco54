<?php
/**
 * Created by PhpStorm.
 * User: Halain
 * Date: 11/8/2018
 * Time: 9:44:14
 */

namespace App\Classes;

use App\User;
use Request;
use App\LogActivities as LogActivityModel;

class LogActivity
{

    public static function addToLog($subject,$user=null,$old_value=null,$new_value=null)
    {
        $log = [];

        $log['user_type'] = auth()->check() ? auth()->user()->getRoleNames(): $user->getRoleNames() ;
        $log['user_id'] = auth()->check() ? auth()->user()->id : $user->id;
        $log['subject'] = $subject;
        $log['old_values'] =$old_value ;
        $log['new_values'] = $new_value;
//        $log['url'] = Request::fullUrl();
        $log['url'] = Request::fullUrlWithQuery([]);
        $log['method'] = Request::method();
        $log['ip_address'] = Request::ip();
        $log['user_agent'] =Request::header('User-Agent');
//        $log['user_agent'] = Request::header('user-agent');


        LogActivityModel::create($log);
    }

    public static function logActivityLists()
    {
        return LogActivityModel::latest()->with('user')->get();
    }

    public static function addToPaymentLog($subject,$payment,$message)
    {
        $log = [];

        $log['user_type'] = 'paymentez_callback' ;
        $log['user_id'] = $payment->user_id;
        $log['subject'] = $subject;
        $log['old_values'] =$message ;
        $log['new_values'] = null;
//        $log['url'] = Request::fullUrl();
        $log['url'] = Request::fullUrlWithQuery([]);
        $log['method'] = Request::method();
        $log['ip_address'] = Request::ip();
        $log['user_agent'] =Request::header('User-Agent');
//        $log['user_agent'] = Request::header('user-agent');


        LogActivityModel::create($log);
    }


}