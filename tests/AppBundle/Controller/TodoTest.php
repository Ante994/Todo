<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 21.11.18.
 * Time: 20:32
 */

namespace Tests\AppBundle\Controller;


use AppBundle\Entity\Todo;
use PHPUnit\Framework\TestCase;

class TodoTest extends TestCase
{
    public function testItHasNoTasksByDefault()
    {
        $todo = new Todo();

        $this->assertEmpty($todo->getTasks());
    }


}