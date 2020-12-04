<?php
namespace App\Logger\Processor;

use Ramsey\Uuid\Uuid;

/**
 * Class UniqueToken
 * @package App\Logger\Processor
 */
class UniqueToken
{
    private ?string $sessionId=null;


    /**
     * @param array<mixed> $record
     * @return array<mixed>
     */
    public function processRecord(array $record)
    {


        if (!$this->sessionId) {
            $id = Uuid::uuid4();
            $this->sessionId = $id->toString();
        }

        $record['extra']['token'] = $this->sessionId;

        return $record;
    }
}
