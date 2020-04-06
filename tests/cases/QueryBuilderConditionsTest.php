<?php

use TenQuality\WP\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @author Local Vibes <https://localvibes.co/> Hyper Tribal
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
                'BETWEEN `a.min` AND %d'
            ],
            [
                [
                    'key' => 'a.min',
                    'operator' => 'BETWEEN',
                    'key_b' => 'a.max',
                ],
                'BETWEEN `a.min` AND `a.max`'
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
                'BETWEEN %d AND `a.max`'
            ],
        ];
    }
}