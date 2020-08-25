<?php

namespace TenQuality\WP\Database\Traits;

use TenQuality\WP\Database\QueryBuilder;

/**
 * Static methods for data models.
 *
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.12
 */
trait DataModelTrait
{
    /**
     * Static constructor that finds recond in database
     * and fills model.
     * @since 1.0.0
     * 
     * @param mixed $id
     * 
     * @return \TenQuality\WP\Database\Abstracts\DataModel|null
     */
    public static function find( $id )
    {
        $model = new self( [], $id );
        return $model->load();
    }
    /**
     * Static constructor that finds recond in database
     * and fills model using where statement.
     * @since 1.0.0
     * 
     * @param array $args Where query statement arguments. See non-static method.
     * 
     * @return \TenQuality\WP\Database\Abstracts\DataModel
     */
    public static function find_where( $args )
    {
        $model = new self;
        return $model->load_where( $args );
    }
    /**
     * Static constructor that inserts recond in database and fills model.
     * @since 1.0.0
     * 
     * @param array $attributes
     * 
     * @return \TenQuality\WP\Database\Abstracts\DataModel
     */
    public static function insert( $attributes )
    {
        $model = new self( $attributes );
        return $model->save( true ) ? $model : null;
    }
    /**
     * Static constructor that deletes records
     * @since 1.0.0
     * 
     * @param array $args Where query statement arguments. See non-static method.
     * 
     * @return bool
     */
    public static function delete_where( $args )
    {
        $model = new self;
        return $model->_delete_where( $args );
    }
    /**
     * Returns a collection of models.
     * @since 1.0.0
     * 
     * @return array
     */
    public static function where( $args = [] )
    {
        // Pull specific data from args
        $limit = isset( $args['limit'] ) ? $args['limit'] : null;
        unset( $args['limit'] );
        $offset = isset( $args['offset'] ) ? $args['offset'] : 0;
        unset( $args['offset'] );
        $keywords = isset( $args['keywords'] ) ? $args['keywords'] : null;
        unset( $args['keywords'] );
        $keywords_separator = isset( $args['keywords_separator'] ) ? $args['keywords_separator'] : ' ';
        unset( $args['keywords_separator'] );
        $order_by = isset( $args['order_by'] ) ? $args['order_by'] : null;
        unset( $args['order_by'] );
        $order = isset( $args['order'] ) ? $args['order'] : 'ASC';
        unset( $args['order'] );
        // Build query and retrieve
        $builder = new QueryBuilder( self::TABLE . '_where' );
        return array_map(
            function( $attributes ) {
                return new self( $attributes );
            },
            $builder->select( '*' )
                ->from( self::TABLE . ' as `' . self::TABLE . '`' )
                ->keywords( $keywords, static::$keywords, $keywords_separator )
                ->where( $args )
                ->order_by( $order_by, $order )
                ->limit( $limit )
                ->offset( $offset )
                ->get( ARRAY_A )
        );
    }
    /**
     * Returns count.
     * @since 1.0.0
     * 
     * @return int
     */
    public static function count( $args = [] )
    {
        // Pull specific data from args
        unset( $args['limit'] );
        unset( $args['offset'] );
        $keywords = isset( $args['keywords'] ) ? sanitize_text_field( $args['keywords'] ) : null;
        unset( $args['keywords'] );
        // Build query and retrieve
        $builder = new QueryBuilder( self::TABLE . '_count' );
        return $builder->from( self::TABLE . ' as `' . self::TABLE . '`' )
            ->keywords( $keywords, static::$keywords )
            ->where( $args )
            ->count();
    }
    /**
     * Returns initialized builder with model set in from statement.
     * @since 1.0.0
     * 
     * @return \TenQuality\WP\Database\Utility\QueryBuilder
     */
    public static function builder()
    {
        $builder = new QueryBuilder( self::TABLE . '_custom' );
        return $builder->from( self::TABLE . ' as `' . self::TABLE . '`' );
    }
    /**
     * Returns a collection with all models found in the database.
     * @since 1.0.7
     * 
     * @return array
     */
    public static function all()
    {
        // Build query and retrieve
        $builder = new QueryBuilder( self::TABLE . '_all' );
        return array_map(
            function( $attributes ) {
                return new self( $attributes );
            },
            $builder->select( '*' )
                ->from( self::TABLE . ' as `' . self::TABLE . '`' )
                ->get( ARRAY_A )
        );
    }
    /**
     * Returns query results from mass update.
     * @since 1.0.12
     * 
     * @param array $set   Set of column => data to update.
     * @param array $where Where condition.
     * 
     * @return \TenQuality\WP\Database\Abstracts\DataModel|null
     */
    public static function update_all( $set, $where = [] )
    {
        $builder = new QueryBuilder( self::TABLE . '_static_update' );
        return $builder->from( self::TABLE )
            ->set( $set )
            ->where( $where )
            ->update();
    }
}