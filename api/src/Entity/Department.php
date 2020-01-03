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
 * @UniqueEntity(fields="id", message="This department already exists.")
 */
class Department {
    /**
     * @var string INSEE code, id of the entity 
     * 
     * @ORM\Id
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank
     * @AcmeAssert\DepCode
     */
    public $id;

    /**
     * @var string name
     * 
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    public $name;

    /** 
     * State
     * @ORM\ManyToOne(targetEntity="State")
     * @JoinColumn(name="state_insee", referencedColumnName="id")
     */
    public $state;

    /**
     * Gets the id of the entity
     * @return id id of the entity
     */
    public function getId() {
        return $this->id;
    }
}

?>