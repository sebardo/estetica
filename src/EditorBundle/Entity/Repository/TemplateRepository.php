<?php

namespace EditorBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * Class TemplateRepository
 */
class TemplateRepository extends EntityRepository
{
    /**
     * Count the total of rows
     *
     * @return int
     */
    public function countTotal()
    {
        $qb = $this->getQueryBuilder()
            ->select('COUNT(t)');

        return $qb->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Find all rows filtered for DataTables
     *
     * @param string $search        The search string
     * @param int    $sortColumn    The column to sort by
     * @param string $sortDirection The direction to sort the column
     * @param int    $entityId      The id related 
     *
     * @return \Doctrine\ORM\Query
     */
    public function findAllForDataTables($search, $sortColumn, $sortDirection, $support=null, $category=null)
    {
        // select
        $qb = $this->getQueryBuilder()
            ->select('t.id, t.status, t.support, t.category, t.backgroundImage image');

        // join
//        $qb->leftJoin('c.client', 'cli')
//           ->leftJoin('c.reviewer', 'r');

        // search
        if (!empty($search)) {
            $qb->where('t.status = :search')
                ->setParameter('search', $search);
        }
        
        if (!is_null($support) && !is_null($category)) {
            $qb->where('t.support = :support')
               ->andWhere('t.category = :category')
                ->setParameter('support', $support)
                ->setParameter('category', $category)    
                    ;
        }elseif(!is_null($support)){
            $qb->where('t.client = :client')
                ->setParameter('client', $support);
        }else{
            $qb->where('t.parentTemplate IS NULL');
        }
        
        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('t.id', $sortDirection);
                break;
            case 1:
                $qb->orderBy('t.status', $sortDirection);
                break;
        }

        // group by
        $qb->groupBy('t.id');

        return $qb->getQuery();
    }

    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();

        $qb = $em->getRepository('EditorBundle:Template')
            ->createQueryBuilder('t');

        return $qb;
    }
}