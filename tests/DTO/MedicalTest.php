<?php

namespace App\Tests\DTO;

use App\DTO\Medical;
use PHPUnit\Framework\TestCase;

class MedicalTest extends TestCase
{
    const NAME = 'test';
    const FIELD = 'test';
    const PHONE = 'test';

    private Medical $dto;

    protected function setUp() :void {

        $this->dto = new Medical(self::NAME,self::FIELD, self::PHONE);
    }

    /**
     * @cover \App\DTO\Medical::__construct
     */
    public function testConstruct()
    {
        $this->assertIsObject($this->dto);
        $this->assertInstanceOf(Medical::class, $this->dto);
    }

    /**
     * @covers \App\DTO\Medical::getName
     */
    public function testGetName()
    {
        $result = $this->dto->getName();

        $this->assertIsString($result);
        $this->assertEquals(self::NAME, $result);

    }

    /**
     * @covers \App\DTO\Medical::getPhone
     */
    public function testGetPhone()
    {
        $result = $this->dto->getPhone();

        $this->assertIsString($result);
        $this->assertEquals(self::PHONE, $result);
    }

    /**
     * @covers \App\DTO\Medical::getField
     */
    public function testGetField()
    {
        $result = $this->dto->getField();

        $this->assertIsString($result);
        $this->assertEquals(self::FIELD, $result);
    }
}
