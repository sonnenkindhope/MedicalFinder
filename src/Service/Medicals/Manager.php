<?php
namespace App\Service\Medicals;

use App\DTO\Medical;
use App\Exception\InternalException;
use App\Exception\NoContentException;
use App\Mapper\Medical\MedicalInterface;
use App\Mapper\Medical\PDO;

/**
 * Class Manager
 * @package App\Service\Medicals
 */
class Manager implements \Psr\Log\LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;

    /**
     *
     *
     * @var MedicalInterface
     */
    private MedicalInterface $database;

    /**
     * Manager constructor.
     *
     * @param PDO $database
     */
    public function __construct(MedicalInterface $database)
    {
        $this->database = $database;
    }

    /**
     * @param  string $term
     * @return Medical[]
     * @throws NoContentException|InternalException
     */
    public function search(string $term) : array
    {
        $this->logger->debug(__FUNCTION__);

        try {
            $result = $this->database->searchBySymptom($term);

            if (count($result) == 0) {
                throw new NoContentException();
            }

            return $result;
        } catch (\PDOException $e) {
            throw new InternalException();
        }
    }

    /**
     * @param  Medical[] $items
     * @return array<mixed>
     */
    public function mapForJson(array $items)
    {
        $this->logger->debug(__FUNCTION__);
        $result = [];

        foreach ($items as $item) {
            $medical = [];
            $medical['name'] = $item->getName();
            $medical['phone'] = $item->getPhone();
            $medical['field'] = $item->getField();

            $result[] = $medical;
        }

        return $result;
    }
}
