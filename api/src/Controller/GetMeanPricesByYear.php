<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetMeanPricesByYear 
{
    /**
     * Entity Manager 
     */
    private $em;

    /**
     * Creates a new instance of GetMeanPricesByYear
     */
    public function __construct(EntityManagerInterface $em) 
    {
        $this->em = $em;
    }

    public function __invoke(Request $data) 
    {
        // Prepares the request for the average price per square metre per month for each year. 
        $request = "SELECT
                        DATE_TRUNC('month', c.mutationDate) AS current_date,
                        AVG(c.value / c.surface) AS mean
                    FROM App:LandValueClaim c
                    WHERE c.type LIKE 'Appartement' OR c.type LIKE 'Maison'
                    GROUP BY current_date
                    ORDER BY current_date";

        // Execute query.
        $query_result = $this->em
                ->createQuery($request)
                ->getResult();

        // Map query results.
        $result = array_map(function($data) {
            $data['mean'] = floatval($data['mean']);
            return $data;
        }, $query_result);
        
        // Build response.
        $response = new Response(
            json_encode($result), 
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
        
        return $response;
    }
}
?>