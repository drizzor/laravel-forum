<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait Favoritable
{
    // Si une réponse est supprimée les likes associés à celle-ci seront automatiquement supprimé de la table favorites
    protected static function bootFavoritable()
    {
        // $reply = modele associé à cette classe, je peux très bien appeler cette variable $caca, Laravel va tjrs interpreter cela comme étant le modèle Reply dans ce cas-ci
       static::deleting(function ($reply) {
            $reply->favorites->each->delete();
       });
    }

    // Liaison polymophique 1 à *
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited'); // favorited fait référence à favorited_id (je ne sais pas pourquoi _id n'est pas mentionné, sans doute une méca Laravel)
    }
    
    // Crée le like dans la DB
    public function favorite()
    {
        if (! $this->favorites()->where(['user_id' => auth()->id()])->exists())
            return $this->favorites()->create(['user_id' => auth()->id()]);
        else
            return $this->favorites()->where(['user_id' => auth()->id()])->delete();
    }

    public function unfavorite() 
    {
        // On est obligé de d'abord récupéré sous forme de collection l'élément à supprimé, afin que l'activité associé puisse également etre supprimée dans RecordsActivity
        // Une simple requete delete va uniquement supprimé l'élément de la table favorit
        $this->favorites()->where(['user_id' => auth()->id()])->get()->each(function ($favorite) {
            // Ainsi on delete depuis le modèle et RecordActivity pourra rentrer en action
            $favorite->delete();
        });
    }

    public function isFavorited()
    {
        if ($this->favorites === NULL) return false;
        return $this->favorites->where("user_id", auth()->id())->count(); 
    }

    // Permet le fonctionnement de l'appends dans Reply
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
       return $this->favorites->count();
    }
}