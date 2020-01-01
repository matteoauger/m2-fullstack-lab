<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
                        DATE_PART('year', c.mutationDate) AS y,
                        DATE_PART('month', c.mutationDate) AS m,
                        AVG(c.value / c.surface) AS mean
                    FROM App:LandValueClaim c
                    WHERE c.type LIKE 'Appartement' OR c.type LIKE 'Maison'
                    GROUP BY y, m";

        // Execute query.
        $query_result = $this->em
                ->createQuery($request)
                ->getResult();

        // Format result.
        $result = [];
        foreach ($query_result as $data) {
            $year = $data['y'];
            $month = $data['m'];
            $mean = $data['mean'];
            if (!isset($result[$year])) {
                $result[$year] = [];
            }
            $result[$year][$month] = $mean;
        }

        // Build response.
        $res = new Response(
            json_encode($result), 
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );

        return $res;
    }
}
?>