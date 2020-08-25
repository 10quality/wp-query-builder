<?php

namespace TenQuality\WP\Database\Abstracts;

use Exception;
use TenQuality\Data\Model;
use TenQuality\WP\Database\QueryBuilder;

/**
 * Custom table data model.
 *
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.12
 */
abstract class DataModel extends Model
{
    /**
     * Database table name.
     * @since 1.0.0
     * @var string
     */
    protected $table = '';
    /**
     * Reference to primary key column name.
     * @since 1.0.0
     * @var string
     */
    protected $primary_key = 'ID';
    /**
     * List of properties used for keyword search.
     * @since 1.0.0
     * @var array
     */
    protected static $keywords = [];
    /**
     * Default model constructor.
     * @since 1.0.0
     * 
     * @param array $attributes
     * @param mixed $id 
     */
    public function __construct( $attributes = [], $id = null )
    {
        parent::__construct( $attributes );
        if ( ! empty( $id ) )
            $this->attributes[$this->primary_key] = $id;
    }
    /**
     * Returns `tablename` property.
     * @since 1.0.0
     * 
     * @global object Wordpress Data base accessor.
     *
     * @return string
     */
    protected function getTablenameAlias()
    {
        global $wpdb;
        return $wpdb->prefix . $this->table;
    }
    /**
     * Returns list of protected/readonly properties for
     * when saving or updating.
     * @since 1.0.0
     * 
     * @return array
     */
    protected function protected_properties()
    {
        return apply_filters(
            'data_model_' . $this->table . '_excluded_save_fields',
            [$this->primary_key, 'created_at', 'updated_at'],
            $this->tablename
        );
    }
    /**
     * Saves data attributes in database.
     * Returns flag indicating if save process was successful.
     * @since 1.0.0
     * 
     * @global object Wordpress Data base accessor.
     * 
     * @param bool $force_insert Flag that indicates if should insert regardless of ID.
     * 
     * @return bool
     */
    public function save( $force_insert = false )
    {
        global $wpdb;
        $protected = $this->protected_properties();
        if ( ! $force_insert && $this->{$this->primary_key} ) {
            // Update
            $success = $wpdb->update( $this->tablename, array_filter( $this->attributes, function( $key ) use( $protected ) {
                return ! in_array( $key , $protected );
            }, ARRAY_FILTER_USE_KEY ), [$this->primary_key => $this->attributes[$this->primary_key]] );
            if ( $success )
                do_action( 'data_model_' . $this->table . '_updated', $this );
        } else {
            // Insert
            $success = $wpdb->insert( $this->tablename, array_filter( $this->attributes, function( $key ) use( $protected ) {
                return ! in_array( $key , $protected );
            }, ARRAY_FILTER_USE_KEY ) );
            $this->{$this->primary_key} = $wpdb->insert_id;
            $date = date( 'Y-m-d H:i:s' );
            $this->created_at = $date;
            $this->updated_at = $date;
            if ( $success )
                do_action( 'data_model_' . $this->table . '_inserted', $this );
        }
        if ( $success )
            do_action( 'data_model_' . $this->table . '_save', $this );
        return $success;
    }
    /**
     * Loads attributes from database.
     * @since 1.0.0
     * 
     * @global object Wordpress Data base accessor.
     * 
     * @return \TenQuality\WP\Database\Abstracts\DataModel|null
     */
    public function load()
    {
        $builder = new QueryBuilder( $this->table . '_load' );
        $this->attributes = $builder->select( '*' )
            ->from( $this->table )
            ->where( [$this->primary_key => $this->attributes[$this->primary_key]] )
            ->first( ARRAY_A );
        return ! empty( $this->attributes )
            ? apply_filters( 'data_model_' . $this->table, $this )
            : null;
    }
    /**
     * Loads attributes from database based on custome where statements
     * 
     * Samples:
     *     // Simple query
     *     $this->load_where( ['slug' => 'this-example-1'] );
     *     // Compound query with OR statement
     *     $this->load_where( ['ID' => 77, 'ID' => ['OR', 546]] );
     * @since 1.0.0
     * 
     * @global object Wordpress Data base accessor.
     * 
     * @param array $args Query arguments.
     * 
     * @return \TenQuality\WP\Database\Abstracts\DataModel|null
     */
    public function load_where( $args )
    {
        if ( empty( $args ) )
            return null;
        if ( ! is_array( $args ) )
            throw new Exception( 'Arguments parameter must be an array.', 10100 );
        $builder = new QueryBuilder( $this->table . '_load_where' );
        $this->attributes = $builder->select( '*' )
            ->from( $this->table )
            ->where( $args )
            ->first( ARRAY_A );
        return ! empty( $this->attributes )
            ? apply_filters( 'data_model_' . $this->table, $this )
            : null;
    }
    /**
     * Deletes record.
     * @since 1.0.0
     * 
     * @global object Wordpress Data base accessor.
     * 
     * @return bool
     */
    public function delete()
    {
        global $wpdb;
        $deleted = $this->{$this->primary_key}
            ? $wpdb->delete( $this->tablename, [$this->primary_key => $this->attributes[$this->primary_key]] )
            : false;
        if ( $deleted )
            do_action( 'data_model_' . $this->table . '_deleted', $this );
        return $deleted;
    }
    /**
     * Updates specific columns of the model (not the whole object like save()).
     * @since 1.0.12
     *
     * @param array $data Data to update.
     *
     * @return bool
     */
    public function update( $data = [] )
    {
        // If not data, let save() handle this
        if ( empty( $data ) || !is_array( $data ) ) {
            return $this->save();
        }
        global $wpdb;
        $success = false;
        $protected = $this->protected_properties();
        if ( $this->{$this->primary_key} ) {
            // Update
            $success = $wpdb->update( $this->tablename, array_filter( $data, function( $key ) use( $protected ) {
                return ! in_array( $key , $protected );
            }, ARRAY_FILTER_USE_KEY ), [$this->primary_key => $this->attributes[$this->primary_key]] );
            if ( $success ) {
                foreach ( $data as $key => $value ) {
                    $this->$key = $value;
                }
                do_action( 'data_model_' . $this->table . '_updated', $this );
            }
        }
        return $success;
    }
    /**
     * Deletes where query.
     * @since 1.0.0
     * 
     * @global object Wordpress Data base accessor.
     * 
     * @param array $args Query arguments.
     * 
     * @return bool
     */
    protected function _delete_where( $args )
    {
        global $wpdb;
        return $wpdb->delete( $this->tablename, $args );
    }
}