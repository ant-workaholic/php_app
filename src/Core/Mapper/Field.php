<?php

namespace Core\Mapper;

/**
 * Class Field
 * @package Core\Mapper
 */
class Field
{
    protected $name = null;
    protected $operator = null;
    protected $comps = [];
    protected $incomplete = false;


    /**
     * Field constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param $operator
     * @param $value
     */
    public function addTest($operator, $value)
    {
        $this->comps[] = [
            "name"      => $this->name,
            "operator" => $operator,
            "value"     => $value
        ];
    }

    /**
     * @return array
     */
    public function getComps()
    {
        return $this->comps;
    }

    /**
     * Verify is the field incomplete.
     *
     * @return bool
     */
    public function isIncomplete()
    {
        return empty($this->comps);
    }
}
