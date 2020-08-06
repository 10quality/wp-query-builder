<?php

use TenQuality\WP\Database\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.11
 */
class SanitizationTest extends TestCase
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
     * @since 1.0.6
     * @group sanitization
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
     * @group sanitization
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
     * @group sanitization
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
     * @group sanitization
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
     * @group sanitization
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
     * @group sanitization
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
     * @group sanitization
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
     * @group sanitization
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
    /**
     * Test negative interger sanitization
     * @since 1.0.7
     * @group sanitization
     */
    public function testSanitizeNegativeInt()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Run
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => '-123',
            ] )
            ->get();
        $args_int = $wpdb->filter_prepare_args(-123);
        $args_string = $wpdb->filter_prepare_args('-123');
        // Assert
        $this->assertTrue(count($args_int) >= 1);
        $this->assertTrue(count($args_string) == 0);
    }
    /**
     * Test query builder
     * @since 1.0.11
     * @group sanitization
     * @group like
     */
    public function testEscLikeWildcard()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => [
                    'operator' => 'LIKE',
                    'value' => 'test{%}es{%}like',
                    'sanitize_callback' => '_builder_esc_like',
                ],
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('esc_like(test)%esc_like(es)%esc_like(like)');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
    /**
     * Test query builder
     * @since 1.0.11
     * @group sanitization
     * @group like
     */
    public function testEscLikeCustomWildcard()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => [
                    'operator' => 'LIKE',
                    'value' => 'test{wild}es{wild}like',
                    'sanitize_callback' => '_builder_esc_like',
                    'wildcard' => '{wild}',
                ],
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('esc_like(test)%esc_like(es)%esc_like(like)');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
    /**
     * Test query builder
     * @since 1.0.11
     * @group sanitization
     * @group like
     */
    public function testEscLikeWildcardDefault()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => [
                    'operator' => 'LIKE',
                    'value' => 'test{%}es{wild}like',
                    'sanitize_callback' => '_builder_esc_like',
                ],
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('esc_like(test)%esc_like(es{wild}like)');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
    /**
     * Test query builder
     * @since 1.0.11
     * @group sanitization
     * @group like
     */
    public function testEscLikeNoWildcard()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => [
                    'operator' => 'LIKE',
                    'value' => 'test%es%like',
                    'sanitize_callback' => '_builder_esc_like',
                ],
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('esc_like(test%es%like)');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
    /**
     * Test query builder
     * @since 1.0.11
     * @group sanitization
     * @group like
     */
    public function testJoinEscLikeWildcard()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'a' )
            ->join( 'b', [
                [
                    'key_a' => 'a.id',
                    'key_b' => 'b.parent_id',
                ],
                [
                    'key_a' => 'b.text',
                    'operator' => 'LIKE',
                    'value' => 'in{%}text',
                    'sanitize_callback' => '_builder_esc_like',
                ],
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('esc_like(in)%esc_like(text)');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
    /**
     * Test query builder
     * @since 1.0.11
     * @group sanitization
     * @group like
     */
    public function testJoinEscLikeCustomWildcard()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'a' )
            ->join( 'b', [
                [
                    'key_a' => 'a.id',
                    'key_b' => 'b.parent_id',
                ],
                [
                    'key_a' => 'b.text',
                    'operator' => 'LIKE',
                    'value' => 'in{wild}text',
                    'sanitize_callback' => '_builder_esc_like',
                    'wildcard' => '{wild}',
                ],
            ] )
            ->get();
        $args = $wpdb->filter_prepare_args('esc_like(in)%esc_like(text)');
        // Assert
        $this->assertTrue(count($args) >= 1);
    }
    /**
     * Test query builder
     * @since 1.0.11
     * @group sanitization
     * @group like
     */
    public function testEscLikeMultipleWildcard()
    {
        // Preapre
        global $wpdb;
        $builder = QueryBuilder::create( 'test' );
        // Prepare
        $builder->select( '*' )
            ->from( 'test_table' )
            ->where( [
                'field' => [
                    'operator' => 'LIKE',
                    'value' => 'with{wild}custom',
                    'sanitize_callback' => '_builder_esc_like',
                    'wildcard' => '{wild}',
                ],
                'field2' => [
                    'operator' => 'LIKE',
                    'value' => 'without{%}custom2',
                    'sanitize_callback' => '_builder_esc_like',
                ],
            ] )
            ->get();
        $with = $wpdb->filter_prepare_args('esc_like(with)%esc_like(custom)');
        $without = $wpdb->filter_prepare_args('esc_like(without)%esc_like(custom2)');
        // Assert
        $this->assertTrue(count($with) >= 1);
        $this->assertTrue(count($without) >= 1);
    }
}