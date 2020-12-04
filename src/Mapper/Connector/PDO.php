<?php

namespace App\Mapper\Connector;

/**
 * Class PDO
 * @package App\Mapper\Connector
 * @codeCoverageIgnore
 */
class PDO implements ConnectorInterface
{
    /** @var \PDO  */
    private \PDO $connection;

    /**
     * PDO constructor.
     * @param string $dns
     * @param string $user
     * @param string $password
     */
    public function __construct(string $dns, string  $user, string  $password)
    {
        $options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_PERSISTENT => true];

        $this->connection = new \PDO($dns, $user, $password, $options);
    }

    /**
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        return $this->connection;
    }



}