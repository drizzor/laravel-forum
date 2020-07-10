<?php

namespace App\Rules;

use Zttp\Zttp;
use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // reCAPTCHA - Ancien code qui était utilisé dans la méthode store ThreadsController
        // $response = Zttp::asFormParams()->post('https://www.google.com/recaptcha/api/siteverify', [
        //     'secret' => config('services.recaptcha.secret'), // voir services.php
        //     'response' => $request->input('g-recaptcha-response'),
        //     'remoteip' => $_SERVER['REMOTE_ADDR']
        // ]);

        // if (!$response->json()['success']) {
        //     throw new \Exception('Recaptcha failed');
        // }

        // Requete Zttp - Outil utilisant Guzzle et simplifiant le mécanisme pour faire une requete http via PHP
        // L'outil est utiliser pour effectuer la requete POST nécessaire à reCAPTCHA
        $response = Zttp::asFormParams()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'), // voir services.php
            'response' => $value,
            'remoteip' => request()->ip()
        ]);

        dd($value);

        return ($response->json()['success']);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Vous avez oublié la validation reCAPTCHA.';
    }
}
