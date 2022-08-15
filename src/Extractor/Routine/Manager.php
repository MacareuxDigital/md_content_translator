<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

class Manager
{
    /**
     * @var array
     */
    protected $routines = [];

    public function registerRoutine(ExtractRoutineInterface $routine)
    {
        $category = $routine->getCategoryHandle();
        if (!isset($this->routines[$category])) {
            $this->routines[$category] = [];
        }

        if (!in_array($routine, $this->routines[$category])) {
            $this->routines[$category][] = $routine;
        }
    }

    /**
     * @param string $handle
     *
     * @return ExtractRoutineInterface[]
     */
    public function getRoutinesByCategory(string $handle): array
    {
        if (isset($this->routines[$handle])) {
            return $this->routines[$handle];
        }

        return [];
    }

    /**
     * @return array
     */
    public function getRoutines(): array
    {
        return $this->routines;
    }
}
