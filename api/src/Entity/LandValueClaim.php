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
     * @var string Service code
     * 
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank 
     */
    public $chServiceCode;

    /**
     * @var string Document reference
     * 
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank
     */
    public $docReference;

    /**
     * @var string Post code
     * 
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank
     * @AcmeAssert\Postcode
     */
    public $postCode;

    /**
     * @var string City
     * 
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank
     */
    public $city;

    /**
     * @var string State code
     * 
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank
     */
    public $stateCode;

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
     * @ORM\Column(type="integer")
     * 
     * @Assert\GreaterThan(0)
     */
    public $value;

    public function getId(): int
    {
        return $this->id;
    }
}

?>
)