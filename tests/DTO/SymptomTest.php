<?php

namespace App\Tests\DTO;

use App\DTO\Symptom;
use PHPUnit\Framework\TestCase;

class SymptomTest extends TestCase
{

    const NUMBER = 1;
    const SYMPTOM = 'test';

    private Symptom $dto;

    protected function setUp() :void {

        $this->dto = new Symptom(self::SYMPTOM,self::NUMBER);
    }

    /**
     * @cover \App\DTO\Symptom::__construct
     */
    public function testConstruct()
    {
        $this->assertIsObject($this->dto);
        $this->assertInstanceOf(Symptom::class, $this->dto);
    }

    /**
     * @covers \App\DTO\Symptom::getNumber
     */
    public function testGetNumber()
    {
        $result = $this->dto->getNumber();

        $this->assertIsInt($result);
        $this->assertEquals(self::NUMBER, $result);
    }


    /**
     * @covers \App\DTO\Symptom::getSymptom
     */
    public function testGetSymptom()
    {
        $result = $this->dto->getSymptom();

        $this->assertIsString($result);
        $this->assertEquals(self::SYMPTOM, $result);
    }
}
