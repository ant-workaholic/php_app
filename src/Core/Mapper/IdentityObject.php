<?php
/**
 *
 */
namespace Core\Mapper;

/**
 * Class IdentityObject
 *
 * @package Core\Mapper
 */
class IdentityObject
{
    /**
     * Current field
     *
     * @var \Core\Mapper\Field
     */
    protected $currentField = null;
    protected $fields       = [];
    private $and            = null;
    private $enforce        = [];

    /**
     * IdentityObject constructor.
     *
     * @param null $field
     * @param array|null $enforce
     */
    public function __construct($field = null, array $enforce = null)
    {
        if (!is_null($enforce)) {
            $this->enforce = $enforce;
        }
        if (!is_null($field)) {
            $this->field($field);
        }
    }

    /**
     * Retrieve allowed fields.
     *
     * @return array|null
     */
    public function getObjectFields()
    {
        return $this->enforce;
    }

    /**
     * Retrieve a field.
     *
     * @param $fieldName
     * @return $this
     * @throws \Exception
     */
    public function field($fieldName)
    {
        if (!$this->isVoid() && $this->currentField->isIncomplete()) {
            throw new \Exception("Incomplete field.");
        } else {
            $this->currentField = new Field($fieldName);
            $this->fields[$fieldName] = $this->currentField;
        }
        return $this;
    }

    /**
     * Verify is the fields empty.
     *
     * @return bool
     */
    public function isVoid()
    {
        return empty($this->fields);
    }

    /**
     * Enforce field.
     *
     * @param $fieldName
     * @throws \Exception
     */
    public function enforceField($fieldName)
    {
        if (!in_array($fieldName, $this->enforce) && !empty($this->enforce)) {
            $forceList = implode(', ', $this->enforce);
            throw new \Exception("$fieldName is not a correct field for a ($forceList)");
        }
    }

    /**
     * Added equal operator.
     *
     * @param $value
     * @return IdentityObject
     */
    public function eq($value)
    {
        return $this->operator("=", $value);
    }

    /**
     * Lower than operator.
     *
     * @param $value
     * @return IdentityObject
     */
    public function lt($value)
    {
        return $this->operator("=", $value);
    }

    /**
     * Greater than
     *
     * @param $value
     * @return IdentityObject
     */
    public function gt($value)
    {
         return $this->operator(">", $value);
    }

    /**
     * Add an operator.
     *
     * @param $symbol
     * @param $value
     * @return $this
     * @throws \Exception
     */
    private function operator($symbol, $value)
    {
        if ($this->isVoid()) {
            throw new \Exception("The field was not found.");
        }
        $this->currentField->addTest($symbol, $value);
        return $this;
    }

    /**
     * Get comparison array.
     *
     * @return array
     */
    public function getComps()
    {
        $comparisons = [];
        foreach ($this->fields as $key => $field) {
            $comparisons = array_merge($comparisons, $field->getComps);
        }
        return $comparisons;
    }
}
