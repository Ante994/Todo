<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use AppBundle\Entity\User;
use AppBundle\Form\Type\TodoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends Controller
{
    public function homepageAction()
    {
        return $this->render('AppBundle:todo:homepage.html.twig');
    }

    public function indexAction()
    {
        $userTodo = $this->getUser();

        return $this->render('AppBundle:todo:index.html.twig', [
                'todos' => $userTodo->getTodos(),
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

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('AppBundle:todo:create.html.twig', [
                'todoForm'=> $form->createView(),
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

        return $this->render('AppBundle:todo:details.html.twig', [
                'todo' => $usersTodo,
                'tasks' => $usersTodo->getTasks(),
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

        return $this->render('AppBundle:todo:create.html.twig',[
                'todoForm'=> $form->createView(),
            ]
        );
    }

}
