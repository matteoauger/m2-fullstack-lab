<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     */
    public $chServiceCode;

    /**
     * @var string Document reference
     * 
     * @ORM\Column(type="text")
     */
    public $docReference;

    /**
     * @var string Post code
     * 
     * @ORM\Column(type="text")
     */
    public $postCode;

    /**
     * @var string City
     * 
     * @ORM\Column(type="text")
     */
    public $city;

    /**
     * @var string State code
     * 
     * @ORM\Column(type="text")
     */
    public $stateCode;

    /**
     * @var \DateTimeInterface Mutation date
     * 
     * @ORM\Column(type="datetime")
     */
    public $mutationDate;

    /**
     * @var string Mutation type
     * 
     * @ORM\Column(type="text")
     */
    public $mutationType;

    /**
     * @var int Land value in euros
     * 
     * @ORM\Column(type="integer")
     */
    public $value;

    public function getId(): int
    {
        return $this->id;
    }
}

?>
