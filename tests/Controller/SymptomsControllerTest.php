<?php

namespace App\Tests\Controller;

use App\Controller\SymptomsController;
use App\Exception\InternalException;
use App\Exception\NoContentException;
use App\Service\Symptoms\Manager;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SymptomsControllerTest extends TestCase
{
    /**
     * @covers \App\Controller\SymptomsController::__construct
     */
    public function testConstruct() {
        $service = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $obj = new SymptomsController($service);


        $this->assertIsObject($obj);
        $this->assertInstanceOf(SymptomsController::class, $obj);
    }

    /**
     * @covers \App\Controller\SymptomsController::index
     * @covers \App\Controller\SymptomsController::validateTerm
     */
    public function testIndexSearchTerm()
    {

        $service = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $obj = new SymptomsController($service);
        $obj->setLogger(new NullLogger());

        $request = new Request();

        $this->testIndexOK($request, $obj);
        $this->testIndexInvalidSearch($request, $obj);

    }

    /**
     * @param Request $request
     * @param MedicalController $obj
     * @return void
     */
    private function testIndexOK(Request $request, SymptomsController $obj): void
    {
        $request->query->set("param", "test");
        $result = $obj->index($request);
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());
    }

    /**
     * @param Request $request
     * @param MedicalController $obj
     */
    private function testIndexInvalidSearch(Request $request, SymptomsController $obj): void
    {
        $request->query->set("param", "123");
        $result = $obj->index($request);
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
    }

    /**
     * @covers \App\Controller\SymptomsController::index
     * @covers \App\Controller\SymptomsController::validateTerm
     */
    public function testIndexNoContent(): void
    {

        $service = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->method('search')->willThrowException(new NoContentException());

        $obj = new SymptomsController($service);
        $obj->setLogger(new NullLogger());


        $request = new Request();
        $request->query->set("param", "test");

        $result = $obj->index($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $result->getStatusCode());
    }

    /**
     * @covers \App\Controller\SymptomsController::index
     * @covers \App\Controller\SymptomsController::validateTerm
     */
    public function testIndexInternalError(): void
    {

        $service = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->method('search')->willThrowException(new InternalException());

        $obj = new SymptomsController($service);
        $obj->setLogger(new NullLogger());


        $request = new Request();
        $request->query->set("param", "test");

        $result = $obj->index($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $result->getStatusCode());

    }
}
