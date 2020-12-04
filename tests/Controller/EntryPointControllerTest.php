<?php

namespace App\Tests\Controller;

use App\Controller\EntryPointController;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EntryPointControllerTest extends TestCase
{

    /**
     * @cover App\Controller\EntryPointController::__construct
     */
    public function testConstruct()
    {
        $obj = new EntryPointController();

        $this->assertIsObject($obj);
        $this->assertInstanceOf(EntryPointController::class, $obj);

    }
}
