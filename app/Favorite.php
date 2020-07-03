<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    // Permet de voir toutes réponses mise en favoris 
    public function favorited()
    {
        return $this->morphTo();
    }
}
