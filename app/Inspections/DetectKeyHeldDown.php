<?php 

namespace App\Inspections;

use Exception;

class DetectKeyHeldDown
{
    public function detect($body)
    {
        if(preg_match('/(.)\\1{4,}/', $body)) {
            throw new Exception("Votre réponse est considérée comme spam.");            
        }
    }
}