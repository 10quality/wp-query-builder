<?php

use TenQuality\WP\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.9
 */
class FunctionsTest extends TestCase
{
    /**
     * Test function exists
     * @since 1.0.9
     * @group functions
     * @requires function wp_query_builder
     */
    public function testExists()
    {
        $this->assertTrue( function_exists( 'wp_query_builder' ), 'Function wp_query_builder no being autoloaded.' );
    }
    /**
     * Test instance of
     * @since 1.0.0
     * @group functions
     * @requires function wp_query_builder
     */
    public function testInstanceOf()
    {
        // Prepare and run
        $builder = wp_query_builder();
        // Assert
        $this->assertInstanceOf( QueryBuilder::class, $builder );
    }
}