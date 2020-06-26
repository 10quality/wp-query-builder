<?php

use TenQuality\WP\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.8
 */
class QueryBuilderConditionsTest extends TestCase
{
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group building
     * @group where
     * @group between
     * @group condition
     * @dataProvider providerWhereBetween
     * 
     * @param array  $between
     * @param string $expected_sql
     */
    public function testWhereBetween( $between, $expected_sql )
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'table' )
            ->where( [
                'field' => $between,
            ] )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_table WHERE field ' . $expected_sql,
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group building
     * @group join
     * @group between
     * @group condition
     * @dataProvider providerJoinBetween
     * 
     * @param array  $join
     * @param string $expected_sql
     */
    public function testJoinBetween( $join, $expected_sql )
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'a' )
            ->join( 'b', [$join] )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_a JOIN prefix_b ON ' . $expected_sql,
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group building
     * @group where
     * @group between
     * @group condition
     * @expectedException Exception
     * @expectedExceptionMessage "max" or "key_b "parameter must be indicated when using the BETWEEN operator.
     */
    public function testWhereBetweenException()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'table' )
            ->where( [
                'field' => [
                    'operator' => 'Between',
                    'min' => 1,
                ],
            ] );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group building
     * @group join
     * @group between
     * @group condition
     * @expectedException Exception
     * @expectedExceptionMessage "max" or "key_c" parameter must be indicated when using the BETWEEN operator.
     */
    public function testJoinBetweenException()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'table' )
            ->join( 'b', [
                [
                    'operator' => 'between',
                    'key_a' => 'a.price',
                    'key_b' => 'b.min',
                ],
            ] );
    }
    /**
     * Returns testing data sets.
     * @since 1.0.8
     * 
     * @see self::testWhereBetween
     */
    public function providerJoinBetween()
    {
        return [
            [
                [
                    'operator' => 'between',
                    'key_a' => 'a.price',
                    'key_b' => 'b.min',
                    'key_c' => 'b.max',
                ],
                'a.price BETWEEN b.min AND b.max'
            ],
            [
                [
                    'operator' => 'BETWEEN',
                    'key' => 'a.count',
                    'value' => 10,
                    'key_c' => 'b.max',
                ],
                'a.count BETWEEN %d AND b.max'
            ],
            [
                [
                    'operator' => 'Between',
                    'key' => 'a.id',
                    'min' => 1,
                    'max' => 1000,
                ],
                'a.id BETWEEN %d AND %d'
            ],
            [
                [
                    'operator' => 'between',
                    'key' => 'b.date',
                    'min' => '2020-01-01',
                    'max' => 20200303,
                ],
                'b.date BETWEEN %s AND %d'
            ],
            [
                [
                    'operator' => 'between',
                    'key' => 'b.date',
                    'max' => '2020-01-01',
                    'min' => 20200303,
                ],
                'b.date BETWEEN %d AND %s'
            ],
        ];
    }
    /**
     * Returns testing data sets.
     * @since 1.0.8
     * 
     * @see self::testWhereBetween
     */
    public function providerWhereBetween()
    {
        return [
            [
                [
                    'key' => 'a.min',
                    'operator' => 'between',
                    'max' => 123,
                ],
                'BETWEEN a.min AND %d'
            ],
            [
                [
                    'key' => 'a.min',
                    'operator' => 'BETWEEN',
                    'key_b' => 'a.max',
                ],
                'BETWEEN a.min AND a.max'
            ],
            [
                [
                    'value' => 1,
                    'operator' => 'Between',
                    'max' => 5,
                ],
                'BETWEEN %d AND %d'
            ],
            [
                [
                    'min' => '2020-01-01',
                    'operator' => 'between',
                    'max' => 20200303,
                ],
                'BETWEEN %s AND %d'
            ],
            [
                [
                    'value' => 345,
                    'operator' => 'BETWEEN',
                    'key_b' => 'a.max',
                ],
                'BETWEEN %d AND a.max'
            ],
        ];
    }
}