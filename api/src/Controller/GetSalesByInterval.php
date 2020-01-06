<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

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
                
        return $query_result;
    }
}
?>