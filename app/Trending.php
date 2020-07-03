<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    /**
     * Récupère la liste des sujets populaires
     * Retourne les 5 premiers sujets
     */
    public function get()
    {
        return collect(Redis::zrevrange($this->cacheKey(), 0, 4))->map(function ($thread) {
            return json_decode($thread);
        });
    }

    /**
     * Ajoute/Incrémente un sujet cible
     */
    public function push($thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    /**
     * Retoure la clé souhaitée
     */
    protected function cacheKey()
    {
        return 'trending_threads';
    }

    /**
     * Supprime un élément de la liste pour un affichage correcte en cas de supression de Thread populaire
     */
    public function remove($thread)
    {
        // "{\"title\":\"test 1\",\"path\":\"\\/threads\\/accusantium\\/test-1-60\"}"
        Redis::zrem($this->cacheKey(), json_encode([
            'title' => $thread->title,
            'path' =>  $thread->path()
        ]));
    }

    /**
     * Suppression d'une clé et donc une liste incrémentée
     */
    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}
