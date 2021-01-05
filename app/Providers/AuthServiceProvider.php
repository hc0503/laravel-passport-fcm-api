<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Override the email notification for verifying email
        VerifyEmail::toMailUsing(function ($notifiable, $url){
            $mail = new MailMessage;
            $mail->subject('Welcome to Ugigs Stream!!');
            $mail->markdown('emails.verify-email', ['url' => $url]);
            return $mail;
        });
    }
}
