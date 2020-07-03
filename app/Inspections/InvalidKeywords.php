<?php 

namespace App\Inspections;

use Exception;

class InvalidKeywords 
{
    protected $keywords = [
        'yahoo customer support'
    ];

    public function detect($body)
    {
        foreach($this->keywords as $keyword) {
            if(stripos($body, $keyword) !== false) {
                throw new \Exception('Votre réponse est considérée comme spam.');
            }
        }
    }
}