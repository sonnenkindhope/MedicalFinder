<?php
namespace App\Mapper\Medical;

use App\DTO\Medical;

/**
 * Interface MedicalInterface
 * @package App\Mapper\Medical
 */
interface MedicalInterface
{
    /**
     * @param  string $term
     * @return Medical[]
     */
    public function searchBySymptom(string $term) : array;
}
