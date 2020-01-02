<?php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraint as AcmeAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity
 * @ApiResource
 * 
 * @UniqueEntity(fields="$insee", message="This department already exists.")
 */
class Department {
    /**
     * @var int The entity Id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string Department INSEE code
     * 
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank
     * @AcmeAssert\DepCode
     */
    public $insee;

    /**
     * @var string name
     * 
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @var int state insee code
     * 
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="State")
     * @JoinColumn(name="state_id", referencedColumnName="insee")
     */
    public $stateInsee;

    /**
     * Gets the id of the entity
     * @return id id of the entity
     */
    public function getId() {
        return $this->id;
    }
}

?>