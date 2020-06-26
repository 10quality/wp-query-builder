<?php

use TenQuality\WP\Database\QueryBuilder;
/**
 * Global functions.
 *
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.9
 */
if ( !function_exists( 'wp_query_builder' ) ) {
    /**
     * Returns initialized QueryBuilder instance.
     * @since 1.0.9
     * 
     * @param string|null $query_id
     * 
     * @return \TenQuality\WP\Database\QueryBuilder
     */
    function wp_query_builder( $query_id = null )
    {
        return QueryBuilder::create( $query_id );
    }
}