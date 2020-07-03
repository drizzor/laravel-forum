<?php

namespace App\Inspections;

class Spam 
{

    protected $inspections = [
        InvalidKeywords::class,
        DetectKeyHeldDown::class
    ];

    public function detect($body)
    {
       // Detect invalid keywords
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }
       
       // Si exception n'est pas throw alors return false (aucune detection de spam)
       return false;
    }
}