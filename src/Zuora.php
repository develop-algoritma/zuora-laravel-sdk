<?php

namespace Spira\ZuoraSdk;

use Spira\ZuoraSdk\DataObjects\Product;

/**
 * Business logic to interact with Zuora.
 */
class Zuora
{
    /** @var API */
    protected $api;

    public function __construct(API $api)
    {
        $this->api = $api;
    }

    /**
     * Get first resultset of objects from $table.
     *
     * @param $filtered - lambda called with QueryBuilder argument for adding conditions
     * @return DataObject[]|bool
     */
    public function getAll($table, array $columns, $limit = null, \Closure $filtered = null)
    {
        $query = new QueryBuilder($table, $columns);

        if ($filtered) {
            $filtered($query);
        }

        $result = $this->api->query($query->toZoql(), $limit);

        if (empty($result->result->records)) {
            return false;
        }

        // Zuora API returns 1 object itself not in array
        if (is_object($result->result->records)) {
            return [$result->result->records];
        }

        return $result->result->records;
    }

    /**
     * Get one object from $table.
     *
     * @param $filtered - lambda called with QueryBuilder argument for adding conditions
     * @return DataObject
     */
    public function getOne($table, array $columns, \Closure $filtered = null)
    {
        $query = new QueryBuilder($table, $columns);

        if ($filtered) {
            $filtered($query);
        }

        return $this->fetchOne($query);
    }

    /**
     * Get one object from $table by id.
     *
     * @return DataObject
     */
    public function getOneById($table, $columns, $id)
    {
        $query = new QueryBuilder($table, $columns);
        $query->where('id', '=', $id);

        return $this->fetchOne($query);
    }

    // Example of concrete methods for Products

    public function getAllProducts($limit = null, array $columns = null)
    {
        return $this->getAll('Products', $columns ?: Product::getDefaultColumns(), $limit);
    }

    public function getOneProduct($id, array $columns = null)
    {
        return $this->getOneById('Products', $columns ?: Product::getDefaultColumns(), $id);
    }

    // End of example

    protected function fetchOne(QueryBuilder $query)
    {
        $result = $this->api->query($query->toZoql(), 1);

        return !empty($result->result->records) ? $result->result->records : false;
    }
}
