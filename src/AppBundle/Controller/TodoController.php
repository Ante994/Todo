<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use AppBundle\Entity\User;
use AppBundle\Form\Type\TodoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends Controller
{
    public function homepageAction()
    {
        return $this->render('AppBundle:todo:homepage.html.twig');
    }

    public function indexAction()
    {
        $usersTodo = $this->getDoctrine()->getRepository('AppBundle:Todo')->findAllTodoByUser($this->getUser());

        /**
         * @var $paginator /Knp/Component/Pager/Paginator
         */
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $usersTodo,
            1,
            10
        );

        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);

        return $this->render('AppBundle:todo:index.html.twig', [
                'pagination' => $pagination,
                'form' => $form->createView(),
            ]
        );
    }

    public function newAction(Request $request)
    {
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())  {
            $todo = $form->getData();
            $todo->setDate(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $todo->setUser($user);
            $em->persist($todo);
            $em->flush();
            $this->addFlash(
                'notice', 'Todo Added'
            );

            if ($request->isXmlHttpRequest()) {
                $html = '
                <tr>
                  <th>'.$todo->getId().'</th>
                  <td>'.$todo->getName().'</td>
                  <td>'.$todo->getDate()->format("F j, Y, g:i a").'</td>
                  <td>'.$todo->getDeadline()->format("F j, Y, g:i a").'</td>
                  <td><a href="profiler/todos/'.$todo->getId().'" class="btn btn-success">View</a></td>
                  <td><a href="profiler/todos/'.$todo->getId().'/edit" class="btn btn-default">Edit</a></td>
                  <td><a href="profiler/todos/'.$todo->getId().'/delete" class="btn btn-warning">Delete</a></td>
                </tr>
                ';
                $response = new Response(json_encode($html));

                return $response;
            } else {
                return $this->redirectToRoute('todo_index');
            }
        }

        return $this->render('AppBundle:todo:edit.html.twig', [
                'todo'=> $form->createView(),
            ]
        );
    }

    public function deleteAction($todoId)
    {
        $user = $this->getUser();
        $usersTodo = $this->getDoctrine()->getRepository('AppBundle:Todo')->findByUserAndTodo($user, $todoId);
        if (!$user instanceof User || !$usersTodo) {
            throw $this->createNotFoundException("This does not exist or you not allowed be here!");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($usersTodo);
        $em->flush();
        $this->addFlash(
            'notice', 'Todo Removed'
        );

        return $this->redirectToRoute('todo_index');
    }

    public function showAction($todoId)
    {
        $usersTodo = $this->getDoctrine()->getRepository('AppBundle:Todo')->findByUserAndTodo($this->getUser(), $todoId);

        if (!$this->getUser() instanceof User || !$usersTodo) {
            throw $this->createNotFoundException("This does not exist or you not allowed be here!");
        }
        $todoService = $this->get('app.service.todo_statistic');
        $statistic = $todoService->getStatistic($this->getUser(), $todoId);

        $todoTasks = $this->getDoctrine()->getRepository('AppBundle:Task')->findByTodo($usersTodo);

        /**
         * @var $paginator /Knp/Component/Pager/Paginator
         */
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $todoTasks,
            1,
            10
        );

        return $this->render('AppBundle:todo:details.html.twig', [
                'todo' => $usersTodo,
                'pagination' => $pagination,
                'statistic' => $statistic,
            ]
        );
    }

    public function editAction(Request $request, $todoId)
    {
        $usersTodo = $this->getDoctrine()->getRepository('AppBundle:Todo')->findByUserAndTodo($this->getUser(), $todoId);
        if (!$this->getUser() instanceof User || !$usersTodo) {
            throw $this->createNotFoundException("This does not exist or you not allowed be here!");
        }
        $form = $this->createForm(TodoType::class, $usersTodo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            $this->addFlash(
                'notice', 'Todo Added'
            );

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('AppBundle:todo:edit.html.twig',[
                'form'=> $form->createView(),
            ]
        );
    }

}
