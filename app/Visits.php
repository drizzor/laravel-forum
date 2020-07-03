<?php

namespace App;

use Illuminate\Support\Facades\Redis;

/**
 * Cette classe est utile surtout si on avait une application utilisée par des millions d'utilisateurs
 * Dans notre cas on aurait simplement pu faire un increments de l'attribut "visit_count" ajouté à la table threads depuis notre controller show()
 */
class Visits
{
    protected $thread;

    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    /**
     * Retourne le nombre total de vue
     */
    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    /**
     * Enregistre une visite
     * incr() : incrémente de 1 la clé attribuée et unique pour chaque thread
     */
    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    /**
     * Supprime la clé cible
     */
    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    /**
     * Retourne la clé spécifique au thread
     */
    protected function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
}
