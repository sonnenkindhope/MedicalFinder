<?php
namespace App\Mapper\Symptom;

use App\DTO\Symptom;

/**
 * Class PDO
 * @package App\Mapper\Symptom
 */
class PDO implements SymptomsInterface, \Psr\Log\LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;

    /**
     * @var \PDO
     */
    private \PDO $connection;

    /**
     * PDO constructor.
     *
     * @param \App\Mapper\Connector\ConnectorInterface $connector
     */
    public function __construct(\App\Mapper\Connector\ConnectorInterface $connector)
    {
        $this->connection = $connector->getConnection();
    }


    /**
     * @param  string $term
     * @return array<mixed>
     * @throws \PDOException
     */
    public function searchByLetters(string $term): array
    {
        $this->logger->debug(__FUNCTION__);
        try {
            $query = 'SELECT 
                    s.symptom as name,
                    s.id as id                    
                  FROM symptom s
                  WHERE s.symptom LIKE :term';

            $items = $this->execute($query, ["term" => $term.'%']);
            $result = [];
            foreach ($items as $item) {
                $result[] = self::mapSymptom($item);
            }

            return $result;
        } catch (\PDOException $e) {
            $this->logger->info('medical not retrievable', []);
            throw $e;
        }
    }

    /**
     * @param  array<string, string> $item
     * @return Symptom
     */
    private function mapSymptom($item) : Symptom
    {
        return new Symptom($item['name'], intval($item['id']));
    }

    /**
     * @param  string $query
     * @param  array<mixed>  $params
     * @return array<mixed>
     * @throws \PDOException
     */
    private function execute(string $query, array $params) : array
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        $stmt = null;

        return $result;
    }
}
