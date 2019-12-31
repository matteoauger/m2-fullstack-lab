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
        $base_query  = "SELECT c.value, c.surface ";
        $base_query .= "FROM App:LandValueClaim c "; 
        $base_query .= "WHERE (c.type LIKE 'Appartement' OR c.type LIKE 'Maison') ";
        $base_query .= "AND EXTRACT(year FROM c.mutationDate) = ". $year . " ";

        $mean_by_month = [];

        // Here we are going to calculate the average for each month of the given date
        // So we're starting from January (1)
        for ($month = 1; $month <= 12; $month++) {
            $query_month = $base_query . "AND EXTRACT(month FROM c.mutationDate) =  " . $month;

            $results = $this->em
                ->createQuery($query_month)
                ->getResult();
            if (count($results) > 0) {
                $sum = 0;
                for($i = 0; $i < count($results); $i++) {
                    $sum += $results[$i]['value'] / $results[$i]['surface'];  
                }
    
                $mean = $sum / count($results);
                $mean_by_month[$month] = $mean;
            }
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