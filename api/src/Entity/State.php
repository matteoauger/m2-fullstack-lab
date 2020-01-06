<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ApiResource(collectionOperations={},itemOperations={})
 */
class State {

    /**
     * @var int INSEE code, id of the entity
     * 
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true)
     */
    public $id;

    /**
     * @var string Name
     * @ORM\Column(type="string") 
     * @Assert\NotBlank
     */
    public $name;


    public function getId() {
        return $this->id;
    }
}
?>