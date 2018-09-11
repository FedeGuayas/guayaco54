<?php

namespace App\Listeners;

use App\Http\Controllers\UserController;
use App\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogin
{
    protected $user_controller;
    protected $type;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserController $user_controller)
    {
        $this->user_controller=$user_controller;
        $this->type='login_success';
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $this->user_controller->userLoginLog($this->type);
    }
}
