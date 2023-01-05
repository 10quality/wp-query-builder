<?php

use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.13
 */
class HooksTest extends TestCase
{
    /**
     * Resets global hooks.
     * @since 1.0.10
     */
    public function setUp(): void
    {
        $GLOBALS['hooks'] = [];
    }
    /**
     * Test abstract
     * @since 1.0.10
     * @group hooks
     * @requires function wp_query_builder
     */
    public function testGetBuilder()
    {
        // Prepare and run
        wp_query_builder( 'test_get' )->from( 'table' )->get();
        // Assert
        global $hooks;
        $this->assertArrayHasKey( 'query_builder_get_builder', $hooks );
        $this->assertIsArray( $hooks['query_builder_get_builder'] );
        $this->assertArrayHasKey( 'query_builder_get_builder_test_get', $hooks );
        $this->assertIsArray( $hooks['query_builder_get_builder_test_get'] );
        $this->assertArrayHasKey( 'query_builder_get_query', $hooks );
        $this->assertIsString( $hooks['query_builder_get_query'] );
        $this->assertArrayHasKey( 'query_builder_get_query_test_get', $hooks );
        $this->assertIsString( $hooks['query_builder_get_query_test_get'] );
    }
}