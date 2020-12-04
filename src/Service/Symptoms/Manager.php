<?php
namespace App\Service\Symptoms;

use App\DTO\Medical;
use App\DTO\Symptom;
use App\Exception\InternalException;
use App\Exception\NoContentException;
use App\Mapper\Symptom\SymptomsInterface;
use App\Mapper\Symptom\PDO;

/**
 * Class Manager
 * @package App\Service\Symptoms
 */
class Manager implements \Psr\Log\LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;

    /**
     *
     *
     * @var SymptomsInterface
     */
    private SymptomsInterface $database;

    /**
     * Manager constructor.
     *
     * @param PDO $database
     */
    public function __construct(SymptomsInterface $database)
    {
        $this->database = $database;
    }

    /**
     * @param  string $term
     * @return Symptom[]
     * @throws NoContentException|InternalException
     */
    public function search(string $term) : array
    {
        $this->logger->debug(__FUNCTION__);

        try {
            $result = $this->database->searchByLetters($term);

            if (count($result) == 0) {
                throw new NoContentException();
            }

            return $result;
        } catch (\PDOException $e) {
            throw new InternalException();
        }
    }

    /**
     * @param  Symptom[] $items
     * @return array<mixed>
     */
    public function mapForAcJson(array $items) : array
    {
        $this->logger->debug(__FUNCTION__);
        $result = [];

        foreach ($items as $item) {
            $symptom = [];
            $symptom['value'] = $item->getSymptom();
            $symptom['data'] = $item->getSymptom();

            $result[] = $symptom;
        }

        return $result;
    }
}
