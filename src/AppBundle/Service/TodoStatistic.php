<?php

namespace AppBundle\Service;

use AppBundle\Entity\Todo;
use AppBundle\Entity\User;
use AppBundle\Repository\TodoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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

    /**
     * Return full statistic of todo-list
     * @param User $user
     * @param $todoId
     * @return array
     * @Route()
     */
    public function getStatistic(User $user, $todoId):array
    {
        $todo = $this->todoRepository->findByUserAndTodo($user, $todoId);
        $statistic = [
            'percentOfFinishedTasks' => $this->getPercentOfFinishedTasks($todo),
            'remainingTasks' => $this->getNumberOfRemainingTasks($todo),
            'finishedTasks' => $this->getFinishedTasks($todo),
            'status' => $this->getTodoStatus($todo),
            'numberOfTasks' => $this->getNumberOfTasks($todo),
            'timeUntilDeadline' => $this->getTimeUntilDeadline($todo),
        ];

        return $statistic;
    }


    /**
     * Return percentage of finished tasks on todo-list
     * @param Todo $todo
     * @return float|int
     */
    private function getPercentOfFinishedTasks(Todo $todo):float
    {
        $percent = $this->getFinishedTasks($todo) ?
            round(($this->getFinishedTasks($todo) / $this->getNumberOfTasks($todo)) * 100,2) : 0;

        return $percent;
    }


    /**
     * Returns difference between current and deadline date
     * @param Todo $todo
     * @return array
     */
    private function getTimeUntilDeadline(Todo $todo):array
    {
        $datetime = $todo->getDeadline();
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

    /**
     * Return string status of todo-list
     * @param Todo $todo
     * @return string
     */
    private function getTodoStatus(Todo $todo):string
    {
        if ($this->getNumberOfRemainingTasks($todo)) {
            return 'Not Finished';
        }

        return 'Finished';
    }


    /**
     * Return number of tasks
     * @param Todo $todo
     * @return int
     */
    private function getNumberOfTasks(Todo $todo):int
    {
        $counter = 0;

        foreach ($todo->getTasks() as $task) {
            $counter++;
        }

        return $counter;
    }

    /**
     * Return number of finished tasks
     * @param Todo $todo
     * @return int
     */
    private function getFinishedTasks(Todo $todo):int
    {
        $tasks= $todo->getTasks();
        $counter = 0;
        foreach ($tasks as $task) {
            if ($task->getStatus() == 'Done') {
                $counter++;
            }
        }

        return $counter;
    }


    /**
     * Return number of finished tasks
     * @param Todo $todo
     * @return int
     */
    private function getNumberOfRemainingTasks(Todo $todo): int
    {
        return ($this->getNumberOfTasks($todo) - $this->getFinishedTasks($todo));
    }


}


