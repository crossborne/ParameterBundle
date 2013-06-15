<?php

namespace crossborne\ParameterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parameter
 *
 * @ORM\Table(name="parameter_list")
 * @ORM\Entity(repositoryClass="crossborne\ParameterBundle\Entity\ParameterRepository")
 */
class Parameter
{
	const TYPE_NUMBER = 1;
	const TYPE_STRING = 2;
	const TYPE_ENUM = 3;
	const TYPE_BOOLEAN = 4;
	const TYPE_TAGS = 5;

	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Parameter
     *
	 * @ORM\ManyToOne(targetEntity="Parameter", inversedBy="children")
     */
    private $parent;

	/**
	 * @var string
	 * @ORM\Column(name="param_key", type="string", length=30, nullable=true)
	 */
	private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=255)
     */
    private $caption;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="units", type="string", length=10, nullable=true)
     */
    private $units;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="form_helper", type="text", nullable=true)
	 */
	private $formHelpers;

	/**
	 * @var Parameter[]
	 * @ORM\OneToMany(targetEntity="Parameter", mappedBy="parent", cascade="all")
	 */
	private $children;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="required", type="boolean", nullable=true)
	 */
	private $required;

	/** @var array - to tu je kvuli filtrovani */
	private $values = array();

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parent
     *
     * @param Parameter $parent
     * @return Parameter
     */
    public function setParent(Parameter $parent)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \stdClass 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set caption
     *
     * @param string $caption
     * @return Parameter
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    
        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Parameter
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

	/**
	 * Set units
	 *
	 * @param string $units
	 * @return Parameter
	 */
	public function setUnits($units)
	{
		$this->units = $units;

		return $this;
	}

	/**
	 * Get units
	 *
	 * @return string
	 */
	public function getUnits()
	{
		return $this->units;
	}

	/**
	 * Set key
	 *
	 * @param string $key
	 * @return Parameter
	 */
	public function setKey($key)
	{
		$this->key = $key;

		return $this;
	}

	/**
	 * Get units
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

    /**
     * Set sort
     *
     * @param integer $sort
     * @return Parameter
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    
        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

	public function getChildren() {
		return $this->children;
	}

	public function __toString() {
		return $this->getCaption();
	}

	public function addValue($value) {
		$this->values[] = $value;
	}

	public function setValues(array $values) {
		$this->values = $values;
	}

	public function getValues() {
		return $this->values;
	}

	/**
	 * @param string $formHelpers
	 */
	public function setFormHelpers($formHelpers)
	{
		$this->formHelpers = $formHelpers;
	}

	/**
	 * @return string
	 */
	public function getFormHelpers()
	{
		return $this->formHelpers;
	}

	public function getChoices() {
		$values = explode(";", $this->getFormHelpers());
		$return = array();
		foreach ($values as $v) {
			$return[$v] = $v;
		}
		return $return;
	}

	/**
	 * @param boolean $required
	 */
	public function setRequired($required)
	{
		$this->required = $required;
	}

	/**
	 * @return boolean
	 */
	public function isRequired()
	{
		return $this->required;
	}


}
