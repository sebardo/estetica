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
    public function findAllForDataTables($search, $sortColumn, $sortDirection, $support=null, $category=null, $subcategory=null)
    {
        // select
        $qb = $this->getQueryBuilder()
            ->select('t.id, t.name, t.status, t.support, t.category, t.subcategory, t.backgroundImage image, t.previewImage, t.previewImage2');

        // join
//        $qb->leftJoin('c.client', 'cli')
//           ->leftJoin('c.reviewer', 'r');

        // search
        if (!empty($search)) {
            $qb->where('t.name LIKE :search')
                ->orWhere('t.support LIKE :search')
                ->orWhere('t.category LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }
        
        if (!is_null($support) && !is_null($category)&& !is_null($subcategory)) {
            $qb->andWhere('t.support = :support')
               ->andWhere('t.category = :category')
               ->andWhere('t.subcategory = :subcategory')
                ->setParameter('support', $support)
                ->setParameter('category', $category) 
                ->setParameter('subcategory', $subcategory)    
                    ;
            $qb->andWhere('t.parentTemplate IS NULL');
        }elseif(!is_null($support)){
            $qb->andWhere('t.client = :client')
                ->setParameter('client', $support);
        }else{
            
            $qb->andWhere('t.parentTemplate IS NULL');
        }

        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('t.name', $sortDirection);
                break;
            case 1:
                $qb->orderBy('t.support', $sortDirection);
                break;
            case 2:
                $qb->orderBy('t.category', $sortDirection);
                beak;
            case 3:
                $qb->orderBy('t.subcategory', $sortDirection);
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