<?php

namespace ShinraCoder\DataTableHandler;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DataTable
 * @package ShinraCoder\DataTableHandler
 * @author peter.atkins85@gmail.com
 */
class DataTable
{
    /**
     * @var string
     */
    protected $search;

    /**
     * @var
     */
    protected $orderBy;

    /**
     * @var string
     */
    protected $order;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var int
     */
    protected $start;

    /**
     * @var int
     */
    protected $draw;

    /**
     * @var array
     */
    protected $results = [
        'data'            => [],
        'recordsTotal'    => 0,
        'recordsFiltered' => 0,
        'draw'            => 1,
    ];

    /**
     * @var int
     */
    protected $recordsTotal = 0;

    /**
     * @var int
     */
    protected $recordsFiltered = 0;

    /**
     * @var array
     */
    protected $request;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @var array
     */
    protected $fields;

    protected $dataTableQueryManager;

    /**
     * DataTable constructor.
     * @param                                $request
     * @param DataTableQueryManagerInterface $dataTableQueryManager
     */
    public function __construct(DataTableQueryManagerInterface $dataTableQueryManager, Request $request = null)
    {
        $request = $request ?: Request::createFromGlobals();
        $this->init($request->query);
        $this->dataTableQueryManager = $dataTableQueryManager;
        $this->setResults($this->dataTableQueryManager->queryData($this));
    }

    /**
     * Init from query request
     *
     * @param $request
     */
    public function init(ParameterBag $request)
    {
        if (!empty($request->all()['search']['value'])) {
            $this->setSearch(trim($request['search']['value']));
        }

        if (isset($request->all()['draw'])) {
            $this->setDraw((int) $request['draw']);
        }

        if (isset($request->all()['columns'])) {
            $this->setColumns($request['columns']);
        }

        $this->setRequest($request->all())
            ->setOrderBy(isset($request->all()['order'][0]['column']) ? (int) $request->all()['order'][0]['column'] : 0)
            ->setOrder(isset($request->all()['order'][0]['dir']) ? $request->all()['order'][0]['dir'] : 'desc')
            ->setStart(isset($request->all()['start']) ? (int) $request->all()['start'] : 0)
            ->setLength(isset($request->all()['length']) ? (int) $request->all()['length'] : 10);
    }

    /**
     * @param array $columns
     */
    public function setColumns(array $columns)
    {
        foreach ($columns as $column) {
            $this->addColumn($column);

            if (isset($column['data']) && !empty($column['data'])) {
                $this->addField($column['data']);
            }
        }
    }

    /**
     * @param array $column
     * @return $this
     */
    public function addColumn(array $column)
    {
        $this->columns[] = $column;

        return $this;
    }

    public function addField(string $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return int
     */
    public function getDraw()
    {
        return $this->draw;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param $results
     * @return $this
     */
    public function setResults($results)
    {
        $this->results = [
            'data'            => $results,
            'recordsTotal'    => $this->getRecordsTotal(),
            'recordsFiltered' => $this->getRecordsFiltered(),
            'draw'            => $this->getDraw(),
        ];

        return $this;
    }

    /**
     * @return int
     */
    public function getRecordsTotal()
    {
        return $this->recordsTotal;
    }

    /**
     * @param int $recordsTotal
     * @return DataTable
     */
    public function setRecordsTotal($recordsTotal)
    {
        $this->recordsTotal = (int) $recordsTotal;

        return $this;
    }

    /**
     * @return int
     */
    public function getRecordsFiltered()
    {
        return $this->recordsFiltered;
    }

    /**
     * @param int $recordsFiltered
     * @return DataTable
     */
    public function setRecordsFiltered($recordsFiltered)
    {
        $this->recordsFiltered = (int) $recordsFiltered;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param mixed $orderBy
     * @return DataTable
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $order
     * @return DataTable
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param array $request
     * @return DataTable
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @param string $search
     * @return DataTable
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * @param int $length
     * @return DataTable
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @param int $start
     * @return DataTable
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @param int $draw
     * @return DataTable
     */
    public function setDraw($draw)
    {
        $this->draw = $draw;

        return $this;
    }
}
