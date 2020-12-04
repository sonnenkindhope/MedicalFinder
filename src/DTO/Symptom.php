<?php
namespace App\DTO;

/**
 * Class Symptom
 * @package App\DTO
 */
class Symptom
{
    /**
     *
     *
     * @var string
     */
    private string $symptom;

    /**
     *
     *
     * @var int
     */
    private int $number;

    /**
     * Symptom constructor.
     *
     * @param string $symptom
     */
    public function __construct(string $symptom, int $number)
    {
        $this->symptom = $symptom;
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getSymptom(): string
    {
        return $this->symptom;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
}
