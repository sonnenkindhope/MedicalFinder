<?php

namespace App\Tests\Mapper\Medical;

use App\DTO\Medical;
use App\Mapper\Medical\PDO;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class PDOTest extends TestCase
{

    /**
     * @cover \App\Mapper\Medical\PDO::__construct
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
     * @covers \App\Mapper\Medical\PDO::searchBySymptom
     * @covers \App\Mapper\Medical\PDO::execute
     * @covers \App\Mapper\Medical\PDO::mapMedical
     */
    public function testSearchBySymptomOK()
    {
        $items = [["name" => "test", "field" => "test", "phone" => "test"]];
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

        $result = $obj->searchBySymptom('test');
        $this->assertIsArray($result);
        $this->assertInstanceOf(Medical::class, $result[0]);

    }

    /**
     * @covers \App\Mapper\Medical\PDO::searchBySymptom
     * @covers \App\Mapper\Medical\PDO::execute
     * @covers \App\Mapper\Medical\PDO::mapMedical
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

        $result = $obj->searchBySymptom('test');
        $this->assertIsArray($result);
        $this->assertEquals(0, count($result));
    }

    /**
     * @covers \App\Mapper\Medical\PDO::searchBySymptom
     * @covers \App\Mapper\Medical\PDO::execute
     * @covers \App\Mapper\Medical\PDO::mapMedical
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
        $obj->searchBySymptom('test');

    }


}
