<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ApiResource
 */
class State {

    /**
     * @var int The entity Id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string Name
     * @ORM\Column(type="string") 
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @var int INSEE code
     * 
     * @ORM\Column(type="integer")
     */
    public $insee;

    public function getId() {
        return $this->id;
    }
}
?>