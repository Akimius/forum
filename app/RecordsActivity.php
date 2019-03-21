<?php
/**
 * Created by PhpStorm.
 * User: akim
 * Date: 17.03.19
 * Time: 19:24
 */

namespace App;


use function foo\func;

trait RecordsActivity
{

    protected static function bootRecordsActivity() // boot + Name of the Trait will be triggered on the model which uses the trait
    {
        if (auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    /**
     * Fetch all model events that require activity recording.
     *
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return ['created']; // could be several model events: deleted, created, updated
    }


    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event)
    {
        return $event . '_' . strtolower((new \ReflectionClass($this))->getShortname());
    }
}