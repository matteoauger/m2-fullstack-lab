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
        $year = $data->query->get("year");
        
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

        // build response
        $res = new Response(
            json_encode($query_result), 
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
        
        return $res;
    }
}

/*
[
  {
    "stateName": "Centre-Val de Loire",
    "sales": "15.74869620680598806900"
  },
  {
    "stateName": "Bourgogne-Franche-Comté",
    "sales": "0.91546917795370127200"
  },
  {
    "stateName": "Normandie",
    "sales": "2.12640228116909916300"
  },
  {
    "stateName": "Hauts-de-France",
    "sales": "2.27178929201215623000"
  },
  {
    "stateName": "Grand Est",
    "sales": "3.41987768731474880900"
  },
  {
    "stateName": "Bretagne",
    "sales": "13.88492852586950812300"
  },
  {
    "stateName": "Nouvelle-Aquitaine",
    "sales": "25.34423892244775447400"
  },
  {
    "stateName": "Occitanie",
    "sales": "20.50425843244662889700"
  },
  {
    "stateName": "Auvergne-Rhône-Alpes",
    "sales": "8.98866919296139271400"
  },
  {
    "stateName": "Provence-Alpes-Côte d'Azur",
    "sales": "6.58462462011781037800"
  },
  {
    "stateName": "Corse",
    "sales": "0.21104566090121187100"
  }
]
*/

?> 