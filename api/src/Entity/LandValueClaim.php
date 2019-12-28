<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraint as AcmeAssert;

/**
 * Land value claim entity
 *
 * @ApiResource
 * @ORM\Entity
 */
class LandValueClaim
{
    /**
     * @var int The entity Id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string Department code
     * 
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank
     * @AcmeAssert\Depcode
     */
    public $depCode;

    /**
     * @var \DateTimeInterface Mutation date
     * 
     * @ORM\Column(type="datetime")
     * 
     * @Assert\NotBlank
     */
    public $mutationDate; 

    /**
     * @var string Mutation type
     * 
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank
     */
    public $mutationType;

    /**
     * @var int Land value in euros
     * 
     * @ORM\Column(type="float")
     * 
     * @Assert\GreaterThan(0)
     */
    public $value;

    /**
     * @var int Land surface in m² (does include the unbuilt land)
     * 
     * @ORM\Column(type="float")
     * @Assert\GreaterThan(10)
     */
    public $surface;

    public function getId(): int
    {
        return $this->id;
    }
}

?>
