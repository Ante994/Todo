<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use AppBundle\Entity\User;
use AppBundle\Form\Type\TodoType;
use AppBundle\Service\NewTodoAjax;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TodoController
 * @package AppBundle\Controller
 */
class TodoController extends Controller
{
    /**
     * Homepage
     * @return Response
     */
    public function homepageAction():Response
    {
        return $this->render('AppBundle:todo:homepage.html.twig');
    }

    /**
     * Show user todos
     * @return Response
     */
    public function indexAction():Response
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

    # razdvojiti u servise, max 20 linija po akciji, param coverter, tested code

    /**
     * Creates new todo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
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
                $todoJsonService = $this->get('app.service.todo_index_json');
                $response = $todoJsonService->getJsonTodo($todo);
                return $response;
            }
        }

        return $this->render('AppBundle:todo:homepage.html.twig');

    }

    /**
     * Delete todo
     * @param $todoId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($todoId):RedirectResponse
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

    /**
     * Show details of todo
     * @param $todoId
     * @return Response
     */
    public function showAction($todoId):Response
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

    /**
     * Edit todo
     * @param Request $request
     * @param $todoId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $todoId):Response
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
