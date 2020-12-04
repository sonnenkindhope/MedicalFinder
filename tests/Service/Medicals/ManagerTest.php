<?php

namespace App\Tests\Service\Medicals;

use App\DTO\Medical;
use App\Exception\InternalException;
use App\Exception\NoContentException;
use App\Mapper\Medical\MedicalInterface;
use App\Service\Medicals\Manager;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ManagerTest extends TestCase
{
    /**
     * @cover \App\Service\Medicals\Manager::__construct
     */
    public function test__construct()
    {
        $mapper = $this->getMockBuilder(MedicalInterface::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $obj = new Manager($mapper);

        $this->assertIsObject($obj);
        $this->assertInstanceOf(Manager::class, $obj);
    }

    /**
     * @covers \App\Service\Medicals\Manager::search
     */
    public function testSearchOK()
    {
        $mapper = $this->getMockBuilder(MedicalInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mapper->method('searchBySymptom')->willReturn([1]);

        $obj = new Manager($mapper);
        $obj->setLogger(new NullLogger());
        $result = $obj->search('test');

        $this->assertIsArray($result);
        $this->assertEquals(1, count($result));
    }

    /**
     * @covers \App\Service\Medicals\Manager::search
     */
    public function testSearchNoContent()
    {
        $mapper = $this->getMockBuilder(MedicalInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mapper->method('searchBySymptom')->willReturn([]);

        $obj = new Manager($mapper);
        $obj->setLogger(new NullLogger());

        $this->expectException(NoContentException::class);
       $obj->search('test');
    }

    /**
     * @covers \App\Service\Medicals\Manager::search
     */
    public function testSearchError()
    {
        $mapper = $this->getMockBuilder(MedicalInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mapper->method('searchBySymptom')->willThrowException(new \PDOException());

        $obj = new Manager($mapper);
        $obj->setLogger(new NullLogger());

        $this->expectException(InternalException::class);
        $obj->search('test');
    }

    /**
     * @covers \App\Service\Medicals\Manager::mapForJson
     */
    public function testMapForJson()
    {
        $mapper = $this->getMockBuilder(MedicalInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $obj = new Manager($mapper);
        $obj->setLogger(new NullLogger());

        $result = $obj->mapForJson([]);
        $this->assertIsArray($result);
        $this->assertEquals(0, count($result));

        $dto = new Medical('t','t','t');
        $result = $obj->mapForJson([$dto]);
        $this->assertIsArray($result);
        $this->assertEquals(1, count($result));
        $this->assertEquals('t', $result[0]['name']);
    }


}
