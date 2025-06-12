<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail; 
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransportFactory; // factory que sabe como crear el transport de sendgrid
use Symfony\Component\Mailer\Transport\Dsn; // ayuda a construir el DSN

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        
    }


    public function boot(): void
    {
        //driver sendgrid para manejar el envio de correos
        Mail::extend('sendgrid', function (array $config = []) {
            $config = $this->app['config']->get('services.sendgrid', []);
            //crea el transport
            return (new SendgridTransportFactory())->create(
                new Dsn(
                    'sendgrid+api',
                    'default',
                    $config['key']
                )
            );
        });
        
    }
}
