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
     * 
     * @param em EntityManger 
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function __invoke(Request $data) {
        $year = $data->query->get("year");

        // getting the number of land value claims for the requested year
        $lvc_count = $this->em->createQuery("SELECT COUNT(lvc) FROM App:LandValueClaim lvc WHERE EXTRACT(year FROM lvc.mutationDate) = " . $year)
            ->getSingleScalarResult();

        // main query
        $query_builder = $this->em->createQueryBuilder();

        $result = $query_builder
            // dividing the count by the total number of lvcs 
            // the count is added to 0.0 in order to convert it to float 
            // because the sql division between two integers also returns an integer
            ->select("s.name AS stateName, ((COUNT(lvc) + 0.0) / " . $lvc_count . ") * 100 AS sales")
            ->from("App:LandValueClaim", "lvc")
            ->leftJoin("lvc.department", "d")
            ->leftJoin("d.state", "s")
            ->where("EXTRACT(year FROM lvc.mutationDate) = " . $year)
            ->groupBy("s")
            ->getQuery()->getResult();

        // build response
        $res = new Response(
            json_encode($result), 
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
        
        return $res;
    }
}
?> 