<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 21.11.18.
 * Time: 20:11
 */

namespace AppBundle\Service;


use AppBundle\Entity\Todo;
use Symfony\Component\HttpFoundation\Response;

class NewTodoAjax
{

    /**
     * Return html ready response for ajax call
     * @param Todo $todo
     * @return Response
     */
    public function getJsonTodo(Todo $todo):Response
    {
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

        return new Response(json_encode($html));
    }

}