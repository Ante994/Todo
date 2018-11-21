<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\Todo;
use AppBundle\Entity\User;
use AppBundle\Form\Type\TaskType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TaskController
 * @package AppBundle\Controller
 */
class TaskController extends Controller
{

    /**
     * New task
     * @param Request $request
     * @param Todo $todo
     * @return Response
     */
    public function newAction(Request $request, Todo $todo):Response
    {
        $usersTodo = $this->getDoctrine()->getRepository('AppBundle:Todo')->findByUserAndTodo($this->getUser(), $todo);
        if (!$this->getUser() instanceof User || !$usersTodo) {
            throw $this->createNotFoundException("This does not exist or you not allowed be here!");
        }
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task = $form->getData();
            $task->setTodo($usersTodo);
            $em->persist($task);
            $em->flush();
            $this->addFlash(
                'notice', 'Task Added'
            );

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('AppBundle:task:create.html.twig', [
                'form'=> $form->createView(),
            ]
        );
    }

    /**
     * Delete task
     * @param Request $request
     * @param Task $task
     * @param Todo $todo
     * @return Response
     */
    public function deleteAction(Request $request, Task $task, Todo $todo):Response
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Task')->find($task);
        $todo = $this->getDoctrine()->getRepository('AppBundle:Todo')->findByUserAndTodo($this->getUser(), $todo);
        if (!$this->getUser() instanceof User || !$task) {
            throw $this->createNotFoundException("This does not exist or you not allowed be here!");
        }
        $em->remove($task);
        $em->flush();
        $this->addFlash(
            'notice', 'Task Removed'
        );

        if ($request->isXmlHttpRequest()) {
            return new Response(json_encode(['success'=>true,'message'=>'Successfully deleted']));
        }

        return $this->render('AppBundle:todo:details.html.twig', [
                'todo' => $todo,
                'tasks' => $todo->getTasks(),
            ]
        );
    }


    /**
     * Show task details
     * @param Task $task
     * @return Response
     */
    public function showAction(Task $task):Response
    {
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($task);
        if (!$this->getUser() instanceof User || !$task) {
            throw $this->createNotFoundException("This does not exist or you not allowed be here!");
        }
        $datetime1 =  $task->getDeadline();
        $datetime2 = new DateTime('now');
        $diff = $datetime2->diff($datetime1);
        if ($datetime1 > $datetime2) {
            $message = sprintf('There is left %u days for finishing task', $diff->days);
        } else {
            $message = sprintf('Task deadline ended %u days ago', $diff->days);
        }

        return $this->render('AppBundle:task:details.html.twig', [
                'task' => $task,
                'todoId' => $task->getTodo()->getId(),
                'message' => $message,
            ]
        );
    }

    /**
     * Edit action for task
     * @param Request $request
     * @param Task $task
     * @param Todo $todo
     * @return Response
     */
    public function editAction(Request $request, Task $task, Todo $todo):Response
    {
        $task = $this->getDoctrine()->getRepository('AppBundle:Task')->find($task);
        if (!$this->getUser() instanceof User || !$task) {
            throw $this->createNotFoundException("This does not exist or you not allowed be here!");
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task = $form->getData();
            $task->setTodo($task->getTodo());
            $em->persist($task);
            $em->flush();
            $this->addFlash(
                'notice', 'Task Added'
            );

            if ($request->isXmlHttpRequest()) {
                return new Response(json_encode(['success'=>true,'message'=>'Successfully edited']));
            }

            $todoService = $this->get('app.service.todo_statistic');
            $statistic = $todoService->getStatistic($this->getUser(), $todo);

            return $this->render('AppBundle:todo:details.html.twig', [
                    'todo' => $task->getTodo(),
                    'tasks' => $task->getTodo()->getTasks(),
                    'statistic' => $statistic,
                ]
            );
        }

        return $this->render('AppBundle:task:edit.html.twig', [
                'form'=> $form->createView(),
                'taskId' => $task->getId(),
            ]
        );
    }
}
