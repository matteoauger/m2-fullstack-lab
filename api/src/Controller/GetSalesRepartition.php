<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Get sales repartition action
 * 
 * Gets the sales repartition by state for a given year 
 */
class GetSalesRepartition {
    
    /**
     * Entity manager
     */
    private $em;
    
    /**
     * Creates a new instance of GetSalesRepartition
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function __invoke(Request $data) {
        $year = $data->query->get('year');
        
        // Prevent SQL Injection.
        if (!preg_match('/^\d+$/', $year)) {
            return  new Response(
                'Bad request',
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/text']
            );
        }

        // Fetch total LVC count for the given year.
        $request_count = "SELECT 
                            COUNT(lvc) FROM App:LandValueClaim lvc
                        WHERE EXTRACT(year FROM lvc.mutationDate) = $year";

        $lvc_total_count = $this->em->createQuery($request_count)->getSingleScalarResult();

        // Prepares the request.
        $request = "SELECT
                        s.name AS stateName,
                        ((COUNT(lvc) + 0.0) / $lvc_total_count) * 100 AS sales
                    FROM App:LandValueClaim lvc
                    LEFT JOIN lvc.department d
                    LEFT JOIN d.state s
                    WHERE EXTRACT(year FROM lvc.mutationDate) = $year
                    GROUP BY s";

        // Execute query.
        $query_result = $this->em
                ->createQuery($request)
                ->getResult();

        // Map query results.
        $result = array_map(function($data) {
            $data['sales'] = floatval($data['sales']);
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