<?php


namespace App\DTO;

/**
 * Class Medical
 * @package App\DTO
 */
class Medical
{
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $field;
    /**
     * @var string
     */
    private string $phone;

    /**
     * Medical constructor.
     * @param string $name
     * @param string $field
     * @param string $phone
     */
    public function __construct(string $name, string $field, string $phone)
    {
        $this->name = $name;
        $this->field = $field;
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }
}
