<?php

namespace EditorBundle\Service\DataTables;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JsonList
 *
 * Returns a list in JSON format.
 */
class JsonList
{
    /** @var integer */
    protected $offset;

    /** @var integer */
    protected $limit;

    /** @var integer */
    protected $sortColumn;

    /** @var string */
    protected $sortDirection;

    /** @var string */
    protected $search;

    /** @var integer */
    protected $echo;

    /** @var ObjectRepository */
    protected $repository;
    
    /** @var Category entity */
    protected $category=null;
    
    /** @var EntityId entity */
    protected $entityId=null;
    
    protected $request;
    
    /**
     * Constructor
     *
     * @param RequestStack $requestStack
     */
    public function __construct(Request $requestStack)
    {
        $this->request = $requestStack;
        $this->offset = intval($this->request->get('iDisplayStart'));
        $this->limit = intval($this->request->get('iDisplayLength'));
        $this->sortColumn = intval($this->request->get('iSortCol_0'));
        $this->sortDirection = $this->request->get('sSortDir_0');
        $this->search = $this->request->get('sSearch');
        $this->echo = intval($this->request->get('sEcho'));

        return $this;
    }

    /**
     * Set the repository
     *
     * @param ObjectRepository $repository
     */
    public function setRepository(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }
    
     /**
     * Set the entityId entity 
     *
     * @param Category $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    }
    
     /**
     * Set the category  
     *
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Get the list
     *
     * @return array
     */
    public function get()
    {
        $totalEntities = $this->repository->countTotal();

        if(!is_null($this->entityId) && !is_null($this->category)){
            $entities = $this->repository->findAllForDataTables($this->search, $this->sortColumn, $this->sortDirection, $this->entityId, $this->category);
        }elseif(!is_null($this->entityId)){
            $entities = $this->repository->findAllForDataTables($this->search, $this->sortColumn, $this->sortDirection, $this->entityId);
        }else{
            $entities = $this->repository->findAllForDataTables($this->search, $this->sortColumn, $this->sortDirection, null);
        }
         
        $totalFilteredEntities = count($entities->getScalarResult());

        // paginate
        $entities->setFirstResult($this->offset)
            ->setMaxResults($this->limit);

        $data = $entities->getResult();


        return array(
            'iTotalRecords'         => $totalEntities,
            'iTotalDisplayRecords'  => $totalFilteredEntities,
            'sEcho'                 => $this->echo,
            'aaData'                => $data
        );
    }
    
}