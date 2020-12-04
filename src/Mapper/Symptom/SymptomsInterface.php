<?php
namespace App\Mapper\Symptom;

use App\DTO\Symptom;

/**
 * Interface SymptomsInterface
 * @package App\Mapper\Symptom
 */
interface SymptomsInterface
{
    /**
     * @param  string $term
     * @return Symptom[]
     */
    public function searchByLetters(string $term) : array;
}
