<?php

namespace App\Tests\Mapper\Symptom;

use App\DTO\Symptom;
use App\Mapper\Symptom\PDO;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class PDOTest extends TestCase
{

    /**
     * @cover \App\Mapper\Symptom\PDO::__construct
     */
    public function test__construct()
    {
        $pdo = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $connector = $this->getMockBuilder(\App\Mapper\Connector\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();
        $connector->method('getConnection')->willReturn($pdo);

        $obj = new PDO($connector);

        $this->assertIsObject($obj);
        $this->assertInstanceOf(PDO::class, $obj);

    }

    /**
     * @covers \App\Mapper\Symptom\PDO::searchByLetters
     * @covers \App\Mapper\Symptom\PDO::execute
     * @covers \App\Mapper\Symptom\PDO::mapSymptom
     */
    public function testSearchByLettersOK()
    {
        $items = [["name" => "test", "id" => 1]];
        $stm = $this->getMockBuilder(\PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $stm->method('execute')->willReturn(true);
        $stm->method('fetchAll')->willReturn($items);

        $pdo = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pdo->method('prepare')->willReturn($stm);

        $connector = $this->getMockBuilder(\App\Mapper\Connector\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();
        $connector->method('getConnection')->willReturn($pdo);

        $obj = new PDO($connector);
        $obj->setLogger(new NullLogger());

        $result = $obj->searchByLetters('test');
        $this->assertIsArray($result);
        $this->assertInstanceOf(Symptom::class, $result[0]);
    }

    /**
     * @covers \App\Mapper\Symptom\PDO::searchByLetters
     * @covers \App\Mapper\Symptom\PDO::execute
     * @covers \App\Mapper\Symptom\PDO::mapSymptom
     */
    public function testSearchBySymptomNoResult()
    {
        $items = [];
        $stm = $this->getMockBuilder(\PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $stm->method('execute')->willReturn(true);
        $stm->method('fetchAll')->willReturn($items);

        $pdo = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pdo->method('prepare')->willReturn($stm);

        $connector = $this->getMockBuilder(\App\Mapper\Connector\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();
        $connector->method('getConnection')->willReturn($pdo);

        $obj = new PDO($connector);
        $obj->setLogger(new NullLogger());

        $result = $obj->searchByLetters('test');
        $this->assertIsArray($result);
        $this->assertEquals(0, count($result));
    }

    /**
     * @covers \App\Mapper\Symptom\PDO::searchByLetters
     * @covers \App\Mapper\Symptom\PDO::execute
     * @covers \App\Mapper\Symptom\PDO::mapSymptom
     */
    public function testSearchBySymptomError()
    {
        $pdo = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pdo->method('prepare')->willThrowException(new \PDOException());

        $connector = $this->getMockBuilder(\App\Mapper\Connector\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();
        $connector->method('getConnection')->willReturn($pdo);

        $obj = new PDO($connector);
        $obj->setLogger(new NullLogger());

        $this->expectException(\PDOException::class);
        $obj->searchByLetters('test');

    }


}
