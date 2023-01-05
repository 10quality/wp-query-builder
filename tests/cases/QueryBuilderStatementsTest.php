<?php

use TenQuality\WP\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.13
 */
class QueryBuilderStatementsTest extends TestCase
{
    /**
     * Reset static.
     * @since 1.0.11
     */
    public function tearDown(): void
    {
        WPDB::reset();
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testSelectStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( 'test_field' )->get();
        // Assert
        $this->assertEquals(
            'SELECT test_field FROM ',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testFromStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( 'test_field' )
            ->from( 'test_table' )->get();
        // Assert
        $this->assertEquals(
            'SELECT test_field FROM prefix_test_table',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testFromNoPrefixStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( 'test_field' )
            ->from( 'test_table', false )->get();
        // Assert
        $this->assertEquals(
            'SELECT test_field FROM test_table',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testWhereStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where([ 'test_field' => 1 ])
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE test_field = %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testWhereNullStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where([ 'test_field' => null ])
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE test_field is null',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testWhereOperatorStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where([ 'test_field' => ['operator' => '<>', 'value' => 1 ]])
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE test_field <> %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testWhereNotNullStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where([ 'test_field' => ['operator' => 'IS NOT', 'value' => null ]])
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE test_field IS NOT null',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testWhereMultipleStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where([ 'test_field' => 1, 'ID' => 99 ])
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE test_field = %d AND ID = %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testWhereMultipleJointStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where([
                'test_field' => 1,
                'ID' => [ 'joint' => 'OR', 'value' => 99]
            ])
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE test_field = %d OR ID = %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testWhereStringStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where(['test_field' => 'a'])
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE test_field = %s',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testWhereArrayStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where(['test_field' => [1,2,3]])
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE test_field = (\'1\',\'2\',\'3\')',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     * @group join
     */
    public function testJoinStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->join( 'test_join', [ ['key' => 'test_field', 'value' => 1] ] )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table JOIN prefix_test_join ON test_field = %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     * @group join
     */
    public function testLeftJoinStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->join( 'test_join', [ ['key' => 'test_field', 'value' => 1] ], true )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table LEFT JOIN prefix_test_join ON test_field = %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     * @group join
     */
    public function testJoinNoPrefixStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->join( 'test_join', [ ['key' => 'test_field', 'value' => 1] ], false, false )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table JOIN test_join ON test_field = %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     * @group join
     */
    public function testJoinMultipleStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->join( 'test_join', [
                ['key' => 'test_field', 'value' => 1],
                ['key_a' => 'test_field', 'key_b' => 'test_field_2'],
            ] )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table JOIN prefix_test_join ON test_field = %d AND test_field = test_field_2',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     * @group join
     */
    public function testJoinNullOperatorStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->join( 'test_join', [
                ['key' => 'field_a', 'value' => null],
                ['key' => 'field_b', 'value' => null, 'operator' => 'is not'],
            ] )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table JOIN prefix_test_join ON field_a is null AND field_b IS NOT null',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     * @group join
     */
    public function testJoinStringJointArrayOperatorStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->join( 'test_join', [
                ['key' => 'field_a', 'value' => 'a'],
                ['key' => 'field_b', 'value' => [1,2], 'operator' => 'IN', 'joint' => 'OR'],
            ] )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table JOIN prefix_test_join ON field_a = %s OR field_b IN (\'1\',\'2\')',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testLimitStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->limit( 2 )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table LIMIT %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testOffsetStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->offset( 2 )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table OFFSET %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testLimitOffsetStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->limit( 2 )
            ->offset( 2 )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table LIMIT %d OFFSET %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testGroupByStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->group_by( 'test_field' )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table GROUP BY test_field',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testGroupByMultipleStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->group_by( 'field_a' )
            ->group_by( 'field_b' )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table GROUP BY field_a,field_b',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testOrderByStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->order_by( 'test_field' )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table ORDER BY test_field ASC',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testOrderByMultipleDescStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->order_by( 'test_a', 'desc' )
            ->order_by( 'test_b' )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table ORDER BY test_a DESC,test_b ASC',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testHavingStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->having( 'count(1) > 0' )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table HAVING count(1) > 0',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testKeywordsStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->keywords( 'word', ['field_a','field_b'] )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE (field_a LIKE %s OR field_b LIKE %s)',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testKeywordsMultipleStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->keywords( 'two words', ['field_a','field_b'] )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE (field_a LIKE %s OR field_b LIKE %s) AND (field_a LIKE %s OR field_b LIKE %s)',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testKeywordsSeparatorStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->keywords( 'two words', ['field_a','field_b'], ',' )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE (field_a LIKE %s OR field_b LIKE %s)',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testKeywordsSeparatorMultipleStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->keywords( 'two,words', ['field_a','field_b'], ',' )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE (field_a LIKE %s OR field_b LIKE %s) AND (field_a LIKE %s OR field_b LIKE %s)',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group building
     */
    public function testAllStatements()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( 'count(1) AS xx' )
            ->from( 'a' )
            ->join( 'b', [['key_a' => 'b.y', 'key_b' => 'a.y']] )
            ->where( ['a.x' => 2] )
            ->group_by('a.x')
            ->order_by('xx')
            ->having( 'count(1) > 0' )
            ->limit(1)
            ->offset(1)
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT count(1) AS xx FROM prefix_a JOIN prefix_b ON b.y = a.y WHERE a.x = %d '
                .'GROUP BY a.x HAVING count(1) > 0 ORDER BY xx ASC LIMIT %d OFFSET %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.3
     * @group query
     * @group building
     */
    public function testWhereRawStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where([
                'test_field'    => 1,
                'raw'           => [
                    'value' => 'a = b',
                    'sanitize_callback' => false,
                ],
            ])
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table WHERE test_field = %d AND a = b',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.3
     * @group query
     * @group building
     */
    public function testJoinRawStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->join( 'test_join', [ ['raw' => 'a = b'], ['key' => 'field_a', 'value' => 4] ] )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_test_table JOIN prefix_test_join ON a = b AND field_a = %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.6
     * @group query
     * @group building
     */
    public function testSelectCalcRowsStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( 'test_field' )->get( OBJECT, null, true );
        // Assert
        $this->assertEquals(
            'SELECT SQL_CALC_FOUND_ROWS test_field FROM ',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.6
     * @group query
     * @group building
     */
    public function testColCalcRowsStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( 'test_field' )->col( 0, true );
        // Assert
        $this->assertEquals(
            'SELECT SQL_CALC_FOUND_ROWS test_field FROM ',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.6
     * @group query
     * @group building
     */
    public function testRowsFoundStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( 'test_field' )->rows_found();
        // Assert
        $this->assertEquals('SELECT FOUND_ROWS()', $wpdb->get_query());
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group building
     * @group join
     * @dataProvider providerJoinTypesStatement
     * 
     * @param string $type          Join type.
     * @param string $expected_join Expected built join.
     */
    public function testJoinTypesStatement( $type, $expected_join )
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'table' )
            ->join( 'join', [ ['key' => 'field', 'value' => 1] ], $type )
            ->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_table ' . $expected_join . ' prefix_join ON field = %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group building
     * @group join
     */
    public function testJoinStatementException()
    {
        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid join type.');
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'table' )
            ->join( 'join', [ ['key' => 'field', 'value' => 1] ], 'Yolo' );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group building
     * @group delete
     */
    public function testDelete()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $var = $builder->from( 'table' )->delete();
        // Assert
        $this->assertIsBool( $var );
        $this->assertTrue( $var );
        $this->assertEquals(
            'DELETE FROM prefix_table',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group building
     * @group delete
     */
    public function testDeleteWhere()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $var = $builder->from( 'table' )
            ->where( ['field' => 1] )
            ->delete();
        // Assert
        $this->assertIsBool( $var );
        $this->assertTrue( $var );
        $this->assertEquals(
            'DELETE FROM prefix_table WHERE field = %d',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group building
     * @group join
     * @group delete
     */
    public function testDeleteJoin()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $var = $builder->from( 'asp' )
            ->join( 'b as b', [
                [
                    'key_a' => 'b_id',
                    'key_b' => 'b.id',
                ]
            ], true )
            ->where( ['b.id' => null] )
            ->delete();
        // Assert
        $this->assertIsBool( $var );
        $this->assertTrue( $var );
        $this->assertEquals(
            'DELETE prefix_asp FROM prefix_asp LEFT JOIN prefix_b as b ON b_id = b.id WHERE b.id is null',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group building
     * @group join
     * @group delete
     */
    public function testDeleteJoinWithAS()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $var = $builder->from( 'asp as asp' )
            ->join( 'b as b', [
                [
                    'key_a' => 'asp.b_id',
                    'key_b' => 'b.id',
                ]
            ], true )
            ->where( ['b.id' => null] )
            ->delete();
        // Assert
        $this->assertIsBool( $var );
        $this->assertTrue( $var );
        $this->assertEquals(
            'DELETE prefix_asp FROM prefix_asp as asp LEFT JOIN prefix_b as b ON asp.b_id = b.id WHERE b.id is null',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group building
     * @group select
     */
    public function testSelectWildcardStatement()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->from( 'table' )->get();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_table',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.12
     * @group query
     * @group building
     * @group set
     * @group update
     */
    public function testUpdateSetTypes()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $var = $builder->from( 'set1' )
            ->set( [
                'a' => 'c',
                'b' => 123,
                'c' => null,
                'd' => [7,8],
            ] )
            ->update();
        // Assert
        $this->assertEquals(
            'UPDATE prefix_set1 SET a = %s,b = %d,c = null,d = \'7,8\'',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.12
     * @group query
     * @group building
     * @group set
     * @group update
     */
    public function testUpdateSetRaw()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $var = $builder->from( 'set2' )
            ->set( [
                'raw' => 'c = 1',
                'b' => [
                    'raw' => 'c+1',
                ],
            ] )
            ->update();
        // Assert
        $this->assertEquals(
            'UPDATE prefix_set2 SET c = 1,b = c+1',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.12
     * @group query
     * @group building
     * @group set
     * @group update
     */
    public function testUpdateSetForceString()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $var = $builder->from( 'set2' )
            ->set( [
                'a' => [
                    'value' => 1,
                    'force_string' => true,
                ],
            ] )
            ->update();
        // Assert
        $this->assertEquals(
            'UPDATE prefix_set2 SET a = %s',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.12
     * @group query
     * @group building
     * @group set
     * @group join
     * @group update
     */
    public function testUpdateJoinWhere()
    {
        // Prepare
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $var = $builder->from( 'u1' )
            ->join( 'u2', [
                [
                    'key_a' => 'u1.id',
                    'key_b' => 'u2.id',
                ]
            ] )
            ->set( [
                'raw' => 'u1.title = u2.title',
                'u1.parent' => 'has_parent',
            ] )
            ->where( [
                'u1.status' => 'test',
            ] )
            ->update();
        // Assert
        $this->assertEquals(
            'UPDATE prefix_u1,prefix_u2 JOIN prefix_u2 ON u1.id = u2.id SET u1.title = u2.title,u1.parent = %s WHERE u1.status = %s',
            $wpdb->get_query()
        );
    }
    /**
     * Returns testing data sets.
     * @since 1.0.8
     * 
     * @see self::testJoinTypesStatement
     */
    public function providerJoinTypesStatement()
    {
        return [
            ['right', 'RIGHT JOIN'],
            ['right OUTeR', 'RIGHT OUTER JOIN'],
            ['CROSS', 'CROSS JOIN'],
            ['left', 'LEFT JOIN'],
            ['left outer', 'LEFT OUTER JOIN'],
            ['Inner', 'INNER JOIN'],
            [' ', 'JOIN'],
            ['', 'JOIN'],
        ];
    }
}