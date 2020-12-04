<?php
namespace App\Mapper\Medical;

use App\DTO\Medical;
use App\Mapper\Connector\ConnectorInterface;

/**
 * Class PDO
 * @package App\Mapper\Medical
 */
class PDO implements MedicalInterface, \Psr\Log\LoggerAwareInterface
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
    public function searchBySymptom(string $term): array
    {
        $this->logger->debug(__FUNCTION__);
        try {
            $query = 'SELECT 
                    concat(trim(d.prefix), " ",(d.firstname)," ", (d.lastname)) as name 
                    , d.phone as phone
                    , d.special_field as field
                  FROM  doctor_symptom_relation dsr
                  JOIN doctor d ON dsr.doctor = d.id 
                  JOIN symptom s ON dsr.symptom = s.id
                  WHERE s.symptom = :term
                  GROUP BY d.id';

            $items = $this->execute($query, ["term" => $term]);
            $result = [];
            foreach ($items as $item) {
                $result[] = self::mapMedical($item);
            }

            return $result;
        } catch (\PDOException $e) {
            $this->logger->info('medical not retrievable', []);
            throw $e;
        }
    }

    /**
     * @param  array<mixed> $item
     * @return Medical
     */
    private function mapMedical($item) : Medical
    {
        return new Medical($item['name'], $item['field'], $item['phone']);
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
