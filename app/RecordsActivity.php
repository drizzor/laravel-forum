<?php 

namespace App;

trait RecordsActivity
{
    // méthode boot comme vu précédemment, mais liée au trait (on doit reprendre le nom de la classe)
    // cela va nous éviter de répeter dans tous nos modèles le code inclus
    protected static function bootRecordsActivity()
    {
        foreach(static::getActivitesToRecord() as $event){
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
           });
        }  
        
        // Suppression automatique de l'activité (table activities) si l'élément associé est supprimé 
        static::deleting(function($model){
            $model->activity()->delete();
        });
    }

    // On peut completer les différents types de requetes devant être surveillé (created, updated,...)
    protected static function getActivitesToRecord()
    {
        return ['created'];
    }

    public function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event), // App/foo/Thread.php -> Va nous permettre de récupérer que le nom de la classe de façon dynamiuqe à savoir "Thread"
        ]);
    }
    
    public function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return $event . '_' . $type;
    }

    // Sert à completer les données de la table activities
    public function activity()
    {
       return $this->morphMany(Activity::class, 'subject');
    }
}