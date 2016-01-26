<?php

namespace Spira\ZuoraSdk;

use Spira\ZuoraSdk\Exception\LogicException;

/**
 * Query builder for ZOQL.
 *
 * @see https://knowledgecenter.zuora.com/BC_Developers/SOAP_API/M_Zuora_Object_Query_Language
 */
class QueryBuilder
{
    protected $table;
    protected $where = [];
    protected $columns = [];
    protected $allowed_operators = ['<', '>', '=', '!=', '<=', '>=', '<>'];

    public function __construct($table, array $columns)
    {
        if (empty($columns)) {
            throw new LogicException('There should be at least one column in QueryBuilder');
        }

        $this->table = $table;
        $this->columns = $columns;
    }

    public function where($column, $operator, $value)
    {
        return $this->addWhere($column, $operator, $value, false);
    }

    public function orWhere($column, $operator, $value)
    {
        return $this->addWhere($column, $operator, $value, true);
    }

    public function toZoql()
    {
        $query = sprintf('SELECT %s FROM %s', implode(', ', $this->columns), $this->table);

        if ($where = $this->buildWhere()) {
            $query .= ' WHERE '.$where;
        }

        return $query;
    }

    protected function buildWhere()
    {
        $where = '';
        foreach ($this->where as $condition) {
            $prefix = $condition['or'] ? ' OR ' : ' AND ';

            $where .= sprintf(
                '%s%s %s %s',
                (! empty($where) ? $prefix : ''),
                $condition['column'],
                $condition['operator'],
                $this->convertValue($condition['value'])
            );
        }

        return $where;
    }

    protected function convertValue($value)
    {
        if (is_null($value)) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_string($value)) {
            return "'{$value}'";
        }

        return $value;
    }

    protected function addWhere($column, $operator, $value, $or)
    {
        if (! in_array($operator, $this->allowed_operators)) {
            throw new LogicException(sprintf('Operator "%s" is not allowed', $operator));
        }

        $this->where[] = compact('column', 'operator', 'value', 'or');

        return $this;
    }
}
