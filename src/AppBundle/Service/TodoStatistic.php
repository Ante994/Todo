<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Repository\TodoRepository;

class TodoStatistic
{
    /**
     * @var TodoRepository
     */
    private $todoRepository;

    /**
     * TodoStatistic constructor.
     * @param TodoRepository $todoRepository
     */

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }


    public function getStatistic(User $user, $todoId)
    {
        $todo = $this->todoRepository->findByUserAndTodo($user, $todoId);
        $statistic = [
            'percentOfFinishedTasks' => $this->getPercentOfFinishedTasks($todo->getTasks()),
            'remainingTasks' => $this->getNumberOfTasks($todo->getTasks()) - $this->getFinishedTasks($todo->getTasks()),
            'finishedTasks' => $this->getFinishedTasks($todo->getTasks()),
            'status' => $this->getTodoStatus($todo->getTasks()),
            'numberOfTasks' => $this->getNumberOfTasks($todo->getTasks()),
            'timeUntilDeadline' => $this->getTimeUntilDeadline($todo->getDeadline()),
        ];

        return $statistic;
    }

    private function getPercentOfFinishedTasks($tasks)
    {
        $percent = $this->getFinishedTasks($tasks) ?
            round($this->getFinishedTasks($tasks) / $this->getNumberOfTasks($tasks),2) : 0;

        return $percent;
    }

    private function getTimeUntilDeadline(\DateTime $datetime)
    {
        $dateNow = new \DateTime('now');
        $interval = $dateNow->diff($datetime);
        $diff = [
            'Years' => $interval->y,
            'Months' => $interval->m,
            'Days' => $interval->d,
            'Hours' => $interval->h,
            'Minute' => $interval->i,
            'Seconds' => $interval->s,
        ];

        return $diff;
    }

    private function getTodoStatus($tasks)
    {
        if ($this->getRemainingTasks($tasks)) {
            return 'Not Finished';
        } else {
            return 'Finished';
        }
    }

    private function getNumberOfTasks($tasks)
    {
        $counter = 0;
        foreach ($tasks as $task) {
            $counter++;
        }
        return $counter;
    }

    private function getFinishedTasks($tasks)
    {
        $counter = 0;
        foreach ($tasks as $task) {
            if ($task->getStatus() == 'Done') {
                $counter++;
            }
        }
        return $counter;
    }

    private function getRemainingTasks($tasks)
    {
        return ($this->getNumberOfTasks($tasks) - $this->getFinishedTasks($tasks));
    }

}


