<?php


namespace App\Utils;

/**
 * Trait RequestValidation
 *
 * @package App\Utils
 */
trait RequestValidation
{
    /**
     * Validate
     * term length / basic security
     *
     * @param  string|null $term
     * @return bool
     */
    private function validateTerm(?string $term) : bool
    {
        $result = true;

        if (    is_null($term)
            || strlen(trim($term)) == 0
            ||  preg_match('/%|\/|\\|\||\?|=|\'|"|\.|[0-9]/i', $term) > 0
        ) {
            $result = false;
        }

        return $result;
    }
}
