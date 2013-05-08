<?php

namespace crossborne\ParameterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use crossborne\ParameterBundle\Model\IParametrized;

/**
 * ParameterValue
 *
 * @ORM\Table(name="parameter_value")
 * @ORM\Entity(repositoryClass="crossborne\ParameterBundle\Entity\ParameterValueRepository")
 */
class ParameterValue
{
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
     * @ORM\ManyToOne(targetEntity="Parameter")
     */
    private $parameter;

    /**
     * @var bigint
     *
     * @ORM\Column(name="target_group_id", type="bigint")
     */
    private $targetGroupId;

    /**
     * @var integer
     *
     * @ORM\Column(name="target_entity_id", type="integer")
     */
    private $targetEntityId;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;


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
     * Set parameter
     *
     * @param Parameter $parameter
     * @return ParameterValue
     */
    public function setParameter(Parameter $parameter)
    {
        $this->parameter = $parameter;
    
        return $this;
    }

    /**
     * Get parameter
     *
     * @return Parameter
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * Set targetGroupId
     *
     * @param integer $targetGroupId
     * @return ParameterValue
     */
    public function setTargetGroupId($targetGroupId)
    {
        $this->targetGroupId = $targetGroupId;
    
        return $this;
    }

    /**
     * Get targetGroupId
     *
     * @return integer 
     */
    public function getTargetGroupId()
    {
        return $this->targetGroupId;
    }

    /**
     * Set targetEntityId
     *
     * @param integer $targetEntityId
     * @return ParameterValue
     */
    public function setTargetEntityId($targetEntityId)
    {
        $this->targetEntityId = $targetEntityId;
    
        return $this;
    }

    /**
     * Get targetEntityId
     *
     * @return integer 
     */
    public function getTargetEntityId()
    {
        return $this->targetEntityId;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return ParameterValue
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

	public function __toString() {
		return (string)$this->getValue();
	}

	public static function generateTargetGroupId(IParametrized $object) {
		return crc32(get_class($object));
	}

	public static function createValueForObject(Parameter $parameter, IParametrized $object) {
		$value = new ParameterValue();
		$value->setTargetEntityId($object->getId());
		$value->setTargetGroupId(self::generateTargetGroupId($object));
		$value->setParameter($parameter);
		return $value;
	}
}
