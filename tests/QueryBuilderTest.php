<?php

use Spira\ZuoraSdk\QueryBuilder;

class QueryBuilderTest extends TestCase
{
    public function testPlainSelect()
    {
        $builder = new QueryBuilder('Products', ['Id', 'Name']);
        $this->assertEquals('SELECT Id, Name FROM Products', $builder->toZoql());
    }

    /**
     * @dataProvider dataWhereConditions
     */
    public function testWhereConditions($value, $expected)
    {
        $builder = new QueryBuilder('Products', ['Id', 'Name']);
        $builder->where('Id', '=', $value);

        $this->assertEquals('SELECT Id, Name FROM Products WHERE Id = ' . $expected, $builder->toZoql());
    }

    public function dataWhereConditions()
    {
        return [
            [12, '12'],
            ['abc', "'abc'"],
            [null, 'null'],
            [true, 'true'],
        ];
    }

    public function testOrConditions()
    {
        $builder = new QueryBuilder('Products', ['Id', 'Name']);
        $builder->where('Id', '>=', 123)
            ->orWhere('Name', '=', 'Ololo');

        $this->assertEquals("SELECT Id, Name FROM Products WHERE Id >= 123 OR Name = 'Ololo'", $builder->toZoql());
    }

    /**
     * @expectedException Spira\ZuoraSdk\Exception\LogicException
     * @expectedExceptionMessage There should be at least one column in QueryBuilder
     */
    public function testErrorEmptyColumns()
    {
        new QueryBuilder('Products', []);
    }

    /**
     * @expectedException Spira\ZuoraSdk\Exception\LogicException
     * @expectedExceptionMessage Operator "@" is not allowed
     */
    public function testBadOperator()
    {
        $builder = new QueryBuilder('Products', ['Id', 'Name']);
        $builder->where('Id', '@', 123);
    }
}