<?php

namespace App\Tests\Controller;

use App\Controller\MedicalController;
use App\Exception\InternalException;
use App\Exception\NoContentException;
use App\Service\Medicals\Manager;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MedicalControllerTest extends TestCase
{
    /**
     * @cover \App\Controller\MedicalController::__construct
     */
    public function testConstruct() {
        $service = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $obj = new MedicalController($service);


        $this->assertIsObject($obj);
        $this->assertInstanceOf(MedicalController::class, $obj);
    }

    /**
     * @covers \App\Controller\MedicalController::index     *
     * @covers \App\Controller\SymptomsController::validateTerm
     */
    public function testIndexSearchTerm()
    {
        $service = $this->getMockBuilder(Manager::class)
                        ->disableOriginalConstructor()
                        ->getMock();
        $obj = new MedicalController($service);
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
    private function testIndexOK(Request $request, MedicalController $obj): void
    {
        $request->query->set("param", "test");
        $result = $obj->index($request);
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());
    }

    /**
     * @param Request $request
     * @param MedicalController $obj
     */
    private function testIndexInvalidSearch(Request $request, MedicalController $obj): void
    {
        $request->query->set("param", "123");
        $result = $obj->index($request);
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $result->getStatusCode());
    }

    /**
     * @covers \App\Controller\MedicalController::index     *
     * @covers \App\Controller\SymptomsController::validateTerm
     */
    public function testIndexNoContent(): void
    {

        $service = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->method('search')->willThrowException(new NoContentException());

        $obj = new MedicalController($service);
        $obj->setLogger(new NullLogger());

        $request = new Request();
        $request->query->set("param", "test");

        $result = $obj->index($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $result->getStatusCode());
    }

    /**
     * @covers \App\Controller\MedicalController::index
     */
    public function testIndexInternalError(): void
    {

        $service = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service->method('search')->willThrowException(new InternalException());

        $obj = new MedicalController($service);
        $obj->setLogger(new NullLogger());


        $request = new Request();
        $request->query->set("param", "test");

        $result = $obj->index($request);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $result->getStatusCode());

    }
}
