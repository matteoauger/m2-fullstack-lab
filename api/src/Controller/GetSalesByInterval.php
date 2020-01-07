<?php
namespace App\Controller;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetSalesByInterval
{
    /**
     * Entity Manager
     */
    private $em;

    /**
     * Creates a new instance of GetSalesByInterval
     */
    public function __construct(EntityManagerInterface $em) 
    {
        $this->em = $em;
    }

    public function __invoke(Request $data) 
    {
        $interval = $data->query->get('interval');
        $date_start = $data->query->get('date_start');
        $date_end = $data->query->get('date_end');

        // Prevent SQL Injection.
        if (!preg_match('/^(day|month|year)$/', $interval)) {
            return new Response(
                'Bad request: Illegal interval',
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/text']
            );
        }

        if (!preg_match('/^\d{1,4}-\d{1,2}-\d{1,2}$/', $date_start)) {
            return new Response(
                'Bad request: Illegal date_start',
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/text']
            );
        }

        if (!preg_match('/^\d{1,4}-\d{1,2}-\d{1,2}$/', $date_end)) {
            return new Response(
                'Bad request: Illegal date_end',
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/text']
            );
        }

        // Prevent inversed dates.
        $start = DateTime::createFromFormat('Y-m-d', $date_start);
        $end = DateTime::createFromFormat('Y-m-d', $date_end);
        if ($end < $start) {
            return new Response(
                'Bad request: date_end must be greater than date_start',
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/text']
            );
        }

        // Prepares the request. 
        $request = "SELECT
                        DATE_TRUNC('$interval', c.mutationDate) AS current_date,
                        COUNT(c.mutationType) AS sales_count
                    FROM App:LandValueClaim c
                    WHERE 
                        c.mutationType LIKE 'Vente' AND 
                        c.mutationDate BETWEEN '$date_start' AND '$date_end'
                    GROUP BY current_date
                    ORDER BY current_date";

        // Execute query.
        $query_result = $this->em
                ->createQuery($request)
                ->getResult();

        // Map query results.
        $result = array_map(function($data) {
            $data['sales_count'] = intval($data['sales_count']);
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