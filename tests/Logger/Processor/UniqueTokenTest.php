<?php

namespace App\Tests\Logger\Processor;

use App\Logger\Processor\UniqueToken;
use PHPUnit\Framework\TestCase;

class UniqueTokenTest extends TestCase
{

    /**
     * @covers \App\Logger\Processor\UniqueToken::processRecord
     */
    public function testProcessRecord()
    {
        $obj = new UniqueToken();

        $this->assertIsObject($obj);
        $this->assertInstanceOf(UniqueToken::class, $obj);

        $result = $obj->processRecord([]);
        $this->assertIsArray($result);
        $this->assertIsString($result['extra']['token']);

        $token = $result['extra']['token'];

        $result = $obj->processRecord([]);
        $this->assertEquals($token,$result['extra']['token'] );

    }
}
