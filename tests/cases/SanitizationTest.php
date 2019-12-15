<?php

use TenQuality\WP\Database\QueryBuilder;

/**
 * Test.
 *
 * @author Local Vibes <https://localvibes.co/> Hyper Tribal
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.6
 */
class SanitizationTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test query builder
     * @since 1.0.6
     */
    public function testSanitizeInt()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => '123',
            ] )
            ->get();
        $args_int = $wpdb->filter_prepare_args(123);
        $args_string = $wpdb->filter_prepare_args('123');
        // Assert
        $this->assertTrue(count($args_int) >= 1);
        $this->assertTrue(count($args_string) == 0);
    }
    /**
     * Test query builder
     * @since 1.0.6
     */
    public function testSanitizeFloat()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => '99.99',
            ] )
            ->get();
        $args_float = $wpdb->filter_prepare_args(99.99);
        $args_string = $wpdb->filter_prepare_args('99.99');
        // Assert
        $this->assertTrue(count($args_float) >= 1);
        $this->assertTrue(count($args_string) == 0);
    }
    /**
     * Test query builder
     * @since 1.0.6
     */
    public function testSanitizeString()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => 'string val',
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('sanitized(string val)');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
    /**
     * Test query builder
     * @since 1.0.6
     */
    public function testSanitizeCustomCallable()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => [
                    'value' => 'string val',
                    'sanitize_callback' => 'custom_sanitize',
                ],
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('custom(string val)');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
    /**
     * Test query builder
     * @since 1.0.6
     */
    public function testEscLike()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => [
                    'operator' => 'LIKE',
                    'value' => 'test esc like',
                    'sanitize_callback' => '_builder_esc_like',
                ],
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('esc_like(test esc like)');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
    /**
     * Test query builder
     * @since 1.0.6
     */
    public function testEscLikeWildValue()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => [
                    'operator' => 'LIKE',
                    'value' => 'test esc like',
                    'sanitize_callback' => '_builder_esc_like_wild_value',
                ],
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('%esc_like(test esc like)');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
    /**
     * Test query builder
     * @since 1.0.6
     */
    public function testEscLikeValueWild()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => [
                    'operator' => 'LIKE',
                    'value' => 'test esc like',
                    'sanitize_callback' => '_builder_esc_like_value_wild',
                ],
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('esc_like(test esc like)%');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
    /**
     * Test query builder
     * @since 1.0.6
     */
    public function testEscLikeWildWild()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => [
                    'operator' => 'LIKE',
                    'value' => 'test esc like',
                    'sanitize_callback' => '_builder_esc_like_wild_wild',
                ],
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('%esc_like(test esc like)%');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
}