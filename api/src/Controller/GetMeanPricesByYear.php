<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\LandValueClaim;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GetMeanPricesByYear {

    /**
     * Entity Manager 
     */
    private $em;

    /**
     * Creates a new instance of GetMeanPricesByYear
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function __invoke(Request $data) {
        $year = $data->query->get('year');
        
        // base dql query selecting the prices and surfaces from the LVCs of the given year 
        $query  = "SELECT ";
        $query .= "EXTRACT(month FROM c.mutationDate) AS m, ";
        $query .= "AVG(c.value/c.surface) AS price_per_meter ";
        $query .= "FROM App:LandValueClaim c "; 
        $query .= "WHERE (c.type LIKE 'Appartement' OR c.type LIKE 'Maison') ";
        $query .= "AND EXTRACT(year FROM c.mutationDate) = ".$year. " ";
        $query .= "GROUP BY m";

        $results = $this->em
                ->createQuery($query)
                ->getResult();

        $mean_by_month = [];
        for ($i = 0; $i < count($results); $i++) {
            $mean_by_month[$results[$i]['m']] = $results[$i]['price_per_meter'];
        }

        $res = new Response(
            json_encode($mean_by_month), 
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );

        return $res;
    }
}
?>