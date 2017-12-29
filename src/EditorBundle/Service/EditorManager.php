<?php

namespace EditorBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EditorManager
 */
class EditorManager
{
    private $entityManager;
    
    private $securityContext;
    
    private $container;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, $securityContext, $container)
    {
        $this->entityManager = $entityManager;
        $this->securityContext = $securityContext;
        $this->container = $container;
    }

    /**
     * Sort entities from the given IDs
     *
     * @param string $entityName
     * @param string $values
     */
    public function sort($entityName, $values)
    {
        $values = json_decode($values);

        for ($i=0; $i<count($values); $i++) {
            $this->entityManager
                ->getRepository($entityName)
                ->createQueryBuilder('e')
                ->update()
                ->set('e.order', $i)
                ->where('e.id = :id')
                ->setParameter('id', $values[$i]->id)
                ->getQuery()
                ->execute();
        }
    }
    
    /**
     * Sets an entity as filtrable
     *
     * @param string $entityName
     * @param int    $id
     *
     * @throws NotFoundHttpException
     * @return boolean
     */
    public function toggleFiltrable($entityName, $id)
    {
        $entity = $this->entityManager->getRepository($entityName)->find($id);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $entity->toggleFiltrable();

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity->isFiltrable();
    }
    
    
}