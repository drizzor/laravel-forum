<?php

namespace App\Rules;

use App\Inspections\Spam;

use Illuminate\Contracts\Validation\Rule;

class SpamFree implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Comme notre méthode detect() ne retourne pas un booléen, j'utilise un try/catch pour corriger cela
        try {
            return !resolve(Spam::class)->detect($value);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Votre message est considéré comme spam.';
    }
}
