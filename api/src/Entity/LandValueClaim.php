<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraint as AcmeAssert;
use App\Controller\GetMeanPricesByYear;
use App\Controller\GetSalesRepartition;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Entity\Department;

/**
 * Land value claim entity
 *
 * @ApiResource(
 * collectionOperations={
 *      "get",
 *      "post",
 *      "meanprices"={
 *         "method"="GET",
 *         "path"="land_value_claims/meanprices",
 *         "controller"=App\Controller\GetMeanPricesByYear::class,
 *         "pagination_enabled"=false,
 *         "read"= false,
 *         "openapi_context"={
 *              "summary"="Gets the mean land value claim price for each month for each year",
 *              "description"="Gets the mean land value claim price for each month for each year",
 *              "read"="false"
 *          }
 *     },
 *     "sales_repartition"={
 *         "method"="GET",
 *         "path"="land_value_claims/salesrepartition",
 *         "controller"=GetSalesRepartition::class,
 *         "pagination_enabled"=false,
 *         "read"= false,
 *         "openapi_context" = {
 *              "summary" = "Gets the sales repartition by state.",
 *              "description" = "Gets the sales repartition by state",
 *              "read"="false",
 *              "parameters"= {
 *                  {
 *                      "in" = "query",
 *                      "name" = "year",
 *                      "required"=true,
 *                      "schema"= {
 *                          "type"="integer",
 *                          "format"="int64"
 *                      },
 *                      "example"= 2015                 
 *                  }             
 *              }
 *          }
 *     },
 *      "salesbyinterval"={
 *          "method"="GET",
 *          "path"="land_value_claims/salesbyinterval",
 *          "controller"=App\Controller\GetSalesByInterval::class,
 *          "pagination_enabled"=false,
 *          "read"=false,
 *          "openapri_context"={
 *              "read"=false,
 *              "parameters"={
 *                  {
 *                      "in"="query",
 *                      "name"="interval",
 *                      "required"= true,
 *                      "schema"= {
 *                          "type"="string",
 *                          "enum"={"day","month","year"}
 *                      },
 *                      "example"="day"
 *                  },
 *                  {
 *                      "in"="query",
 *                      "name"="date_start",
 *                      "required"= true,
 *                      "schema"= {
 *                          "type"="string",
 *                          "format"="full-date"
 *                      },
 *                      "example"="2015-01-01"
 *                  },
 *                  {
 *                      "in"="query",
 *                      "name"="date_end",
 *                      "required"= true,
 *                      "schema"= {
 *                          "type"="string",
 *                          "format"="full-date"
 *                      },
 *                      "example"="2019-12-31"
 *                  }
 *              }
 *          }
 *      }
 * },
 * itemOperations={"get", "patch", "put", "delete"}
 * )
 * @ORM\Entity
 * @ORM\Table(name="land_value_claim",indexes={@ORM\Index(name="mutation_idx", columns={"mutationDate", "mutationType"})})
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
     * @var Department department
     * @ORM\ManyToOne(targetEntity="Department")
     * @JoinColumn(name="dep_code", referencedColumnName="id")
     */
    public $department;

    /**
     * @var \DateTimeInterface Mutation date
     * 
     * @ORM\Column(type="date")
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
     * @var string type
     * 
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank
     */
    public $type;

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
