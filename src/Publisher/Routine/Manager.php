<?php

namespace Macareux\ContentTranslator\Publisher\Routine;

class Manager
{
    /**
     * @var array
     */
    protected $routines = [];

    public function registerRoutine(PublishRoutineInterface $routine)
    {
        if (!in_array($routine, $this->routines)) {
            $this->routines[] = $routine;
        }
    }

    /**
     * @return PublishRoutineInterface[]
     */
    public function getRoutines(): array
    {
        return $this->routines;
    }
}
