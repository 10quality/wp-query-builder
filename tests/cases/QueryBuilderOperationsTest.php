<?php

use TenQuality\WP\Database\QueryBuilder;

/**
 * Test.
 *
 * @author Local Vibes <https://localvibes.co/> Hyper Tribal
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.0
 */
class QueryBuilderOperationsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test query builder
     * @since 1.0.0
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
}