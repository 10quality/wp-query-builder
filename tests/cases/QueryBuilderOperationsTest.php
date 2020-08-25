<?php

use TenQuality\WP\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.12
 */
class QueryBuilderOperationsTest extends TestCase
{
    /**
     * Reset static.
     * @since 1.0.11
     */
    public function tearDown()
    {
        WPDB::reset();
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group execution
     */
    public function testGet()
    {
        // Preapre
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $results = $builder->get();
        // Assert dummy results
        $this->assertInternalType( 'array', $results );
        $this->assertInternalType( 'object', $results[1] );
        $this->assertInstanceOf( 'stdClass', $results[1] );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group execution
     */
    public function testGetArray()
    {
        // Preapre
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $results = $builder->get( ARRAY_A );
        // Assert dummy results
        $this->assertInternalType( 'array', $results );
        $this->assertInternalType( 'array', $results[1] );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group execution
     */
    public function testGetCallable()
    {
        // Preapre
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $results = $builder->get( ARRAY_A, function( $data ) {
            return new Model( $data );
        } );
        // Assert dummy results
        $this->assertInternalType( 'array', $results );
        $this->assertInternalType( 'object', $results[1] );
        $this->assertInstanceOf( 'Model', $results[1] );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group execution
     */
    public function testFirst()
    {
        // Preapre
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $row = $builder->first();
        // Assert dummy results
        $this->assertInternalType( 'object', $row );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group execution
     */
    public function testFirstArray()
    {
        // Preapre
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $row = $builder->first( ARRAY_A );
        // Assert dummy results
        $this->assertInternalType( 'array', $row );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group execution
     */
    public function testValue()
    {
        // Preapre
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $var = $builder->value();
        // Assert dummy results
        $this->assertEquals( 1, $var );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group execution
     */
    public function testCount()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $count = $builder->count();
        // Assert dummy results
        $this->assertEquals( 1, $count );
        $this->assertEquals(
            'SELECT count(1) as `count` FROM ',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group execution
     */
    public function testValueX()
    {
        // Preapre
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $var = $builder->value( 1, 0 );
        // Assert dummy results
        $this->assertEquals( 'type', $var );
    }
    /**
     * Test query builder
     * @since 1.0.0
     * @group query
     * @group execution
     */
    public function testValueY()
    {
        // Preapre
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $var = $builder->value( 0, 1 );
        // Assert dummy results
        $this->assertEquals( 2, $var );
    }
    /**
     * Test query builder
     * @since 1.0.6
     * @group query
     * @group execution
     */
    public function testCol()
    {
        // Preapre
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $columns = $builder->col();
        // Assert dummy results
        $this->assertEquals( [1,2,3,4], $columns );
    }
    /**
     * Test query builder
     * @since 1.0.6
     * @group query
     * @group execution
     */
    public function testCol2()
    {
        // Preapre
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $columns = $builder->col(1);
        // Assert dummy results
        $this->assertEquals( ['type','type','type','type'], $columns );
    }
    /**
     * Test query builder
     * @since 1.0.6
     * @group query
     * @group execution
     */
    public function testRowFound()
    {
        // Preapre
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $var = $builder->rows_found();
        // Assert dummy results
        $this->assertEquals( 1, $var );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group execution
     */
    public function testQuery()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $var = $builder->query( 'SET sql_prop = 1;' );
        // Assert dummy results
        $this->assertInternalType( 'bool', $var );
        $this->assertTrue( $var );
        $this->assertEquals(
            'SET sql_prop = 1;',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group execution
     */
    public function testQueryBuilt()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $var = $builder
            ->from( 'table' )
            ->query();
        // Assert dummy results
        $this->assertInternalType( 'bool', $var );
        $this->assertTrue( $var );
        $this->assertEquals(
            'SELECT * FROM prefix_table',
            $wpdb->get_query()
        );
    }
    /**
     * Test query builder
     * @since 1.0.8
     * @group query
     * @group execution
     */
    public function testRaw()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Exec
        $var = $builder->raw( 'SET sql_prop = 1;' );
        // Assert dummy results
        $this->assertInternalType( 'bool', $var );
        $this->assertTrue( $var );
        $this->assertEquals(
            'SET sql_prop = 1;',
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
    public function testUpdate()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $var = $builder->from( 'up' )
            ->set( [
                'a' => 'abc',
            ] )
            ->update();
        // Assert
        $this->assertInternalType( 'bool', $var );
        $this->assertTrue( $var );
        $this->assertEquals(
            'UPDATE prefix_up SET a = %s',
            $wpdb->get_query()
        );
    }
}