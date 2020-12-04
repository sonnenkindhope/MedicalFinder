<?php

namespace App\Tests\Service\Symptoms;

use App\DTO\Symptom;
use App\Exception\InternalException;
use App\Exception\NoContentException;
use App\Mapper\Symptom\SymptomsInterface;
use App\Service\Symptoms\Manager;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ManagerTest extends TestCase
{
    /**
     * @cover \App\Service\Symptoms\Manager::__construct
     */
    public function test__construct()
    {
        $mapper = $this->getMockBuilder(SymptomsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $obj = new Manager($mapper);

        $this->assertIsObject($obj);
        $this->assertInstanceOf(Manager::class, $obj);
    }

    /**
     * @covers \App\Service\Symptoms\Manager::search
     */
    public function testSearchOK()
    {
        $mapper = $this->getMockBuilder(SymptomsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mapper->method('searchByLetters')->willReturn([1]);

        $obj = new Manager($mapper);
        $obj->setLogger(new NullLogger());
        $result = $obj->search('test');

        $this->assertIsArray($result);
        $this->assertEquals(1, count($result));
    }

    /**
     * @covers \App\Service\Symptoms\Manager::search
     */
    public function testSearchNoContent()
    {
        $mapper = $this->getMockBuilder(SymptomsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mapper->method('searchByLetters')->willReturn([]);

        $obj = new Manager($mapper);
        $obj->setLogger(new NullLogger());

        $this->expectException(NoContentException::class);
        $obj->search('test');
    }

    /**
     * @covers \App\Service\Symptoms\Manager::search
     */
    public function testSearchError()
    {
        $mapper = $this->getMockBuilder(SymptomsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mapper->method('searchByLetters')->willThrowException(new \PDOException());

        $obj = new Manager($mapper);
        $obj->setLogger(new NullLogger());

        $this->expectException(InternalException::class);
        $obj->search('test');
    }

    /**
     * @covers \App\Service\Symptoms\Manager::mapForAcJson
     */
    public function testMapForJson()
    {
        $mapper = $this->getMockBuilder(SymptomsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $obj = new Manager($mapper);
        $obj->setLogger(new NullLogger());

        $result = $obj->mapForAcJson([]);
        $this->assertIsArray($result);
        $this->assertEquals(0, count($result));

        $dto = new Symptom('t',1);
        $result = $obj->mapForAcJson([$dto]);
        $this->assertIsArray($result);
        $this->assertEquals(1, count($result));
        $this->assertEquals('t', $result[0]['value']);
    }
}
