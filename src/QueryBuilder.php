<?php

namespace TenQuality\WP\Database;

use Exception;

/**
 * Database query builder.
 * 
 * @author Local Vibes <https://localvibes.co/> Hyper Tribal
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.6
 */
class QueryBuilder
{
    /**
     * Builder ID for hook references.
     * @since 1.0.0
     * @var string
     */
    protected $id;
    /**
     * Builder statements.
     * @since 1.0.0
     * @var array
     */
    protected $builder;
    /**
     * Builder constructor.
     * @since 1.0.0
     * 
     * @param string $id
     */
    public function __construct( $id = null )
    {
        $this->id = ! empty( $id ) ? $id : uniqid();
        $this->builder = [
            'select'    => [],
            'from'      => null,
            'join'      => [],
            'where'     => [],
            'order'     => [],
            'group'     => [],
            'having'    => null,
            'limit'     => null,
            'offset'    => 0,
        ];
    }
    /**
     * Static constructor.
     * @since 1.0.0
     * 
     * @param string $id
     */
    public static function create( $id = null )
    {
        $builder = new self( $id );
        return $builder;
    }
    /**
     * Adds select statement.
     * @since 1.0.0
     * 
     * @param array|string $statement
     * 
     * @return \TenQuality\WP\Database\QueryBuilder this for chaining.
     */
    public function select( $statement )
    {
        $this->builder['select'][] = $statement;
        return $this;
    }
    /**
     * Adds from statement.
     * @since 1.0.0
     * 
     * @global object $wpdb
     * 
     * @param string $from
     * @param bool   $add_prefix Should DB prefix be added.
     * 
     * @return \TenQuality\WP\Database\QueryBuilder this for chaining.
     */
    public function from( $from, $add_prefix = true )
    {
        global $wpdb;
        $this->builder['from'] = ( $add_prefix ? $wpdb->prefix : '' ) . $from;
        return $this;
    }
    /**
     * Adds keywords search statement.
     * @since 1.0.0
     * 
     * @global object $wpdb
     * 
     * @param string $keywords  Searched keywords.
     * @param array  $columns   Column or fields where to search.
     * @param string $separator Keyword separator within keywords string.
     * 
     * @return \TenQuality\WP\Database\QueryBuilder this for chaining.
     */
    public function keywords( $keywords, $columns, $separator = ' ' )
    {
        if ( ! empty( $keywords ) ) {
            global $wpdb;
            foreach ( explode( $separator , $keywords ) as $keyword ) {
                $keyword = '%' . $this->sanitize_value( true, $keyword ) . '%';
                $this->builder['where'][] = [
                    'joint'     => 'AND',
                    'condition' => '(' . implode( ' OR ', array_map( function( $column ) use( &$wpdb,  &$keyword ) {
                                    return $wpdb->prepare( $column . ' LIKE %s', $keyword );
                                }, $columns ) ) . ')',
                ];
            }
        }
        return $this;
    }
    /**
     * Adds where statement.
     * @since 1.0.0
     * 
     * @global object $wpdb
     * 
     * @param array $args Multiple where arguments.
     * 
     * @return \TenQuality\WP\Database\QueryBuilder this for chaining.
     */
    public function where( $args )
    {
        global $wpdb;
        foreach ( $args as $key => $value ) {
            $arg_value = is_array( $value ) && array_key_exists( 'value', $value ) ? $value['value'] : $value;
            $sanitize_callback = is_array( $value ) && array_key_exists( 'sanitize_callback', $value )
                ? $value['sanitize_callback']
                : true;
            if ( $sanitize_callback )
                $arg_value = $this->sanitize_value( $sanitize_callback, $arg_value );
            $statement = $key === 'raw'
                ? [$arg_value]
                : [
                    $key,
                    is_array( $value ) && isset( $value['operator'] ) ? $value['operator'] : ( $arg_value === null ? 'is' : '=' ),
                    is_array( $value ) && isset( $value['key'] )
                        ? '`' . $value['key'] . '`'
                        : ( is_array( $arg_value )
                            ? ( '(\'' . implode( '\',\'', $arg_value ) . '\')' )
                            : ( $arg_value === null
                                ? 'null'
                                : $wpdb->prepare( is_numeric( $arg_value ) ? '%d' : '%s' , $arg_value )
                            )
                        ),
                ];
            $this->builder['where'][] = [
                'joint'     => is_array( $value ) && isset( $value['joint'] ) ? $value['joint'] : 'AND',
                'condition' => implode( ' ', $statement ),
            ];
        }
        return $this;
    }
    /**
     * Adds join statement.
     * @since 1.0.0
     * 
     * @global object $wpdb
     * 
     * @param string $table      Join table.
     * @param array  $args       Join arguments.
     * @param bool   $left       Flag that indicates if it is "LEFT JOIN"
     * @param bool   $add_prefix Should DB prefix be added.
     * 
     * @return \TenQuality\WP\Database\QueryBuilder this for chaining.
     */
    public function join( $table, $args, $left = false, $add_prefix = true )
    {
        global $wpdb;
        $join = [
            'table' => ( $add_prefix ? $wpdb->prefix : '' ) . $table,
            'left'  => $left,
            'on'    => [],
        ];
        foreach ( $args as $argument ) {
            $arg_value = isset( $argument['value'] ) ? $argument['value'] : null;
            $sanitize_callback = array_key_exists( 'sanitize_callback', $argument ) ? $argument['sanitize_callback'] : true;
            if ( $sanitize_callback )
                $arg_value = $this->sanitize_value( $sanitize_callback, $arg_value );
            $statement = array_key_exists( 'raw', $argument )
                ? $argument['raw']
                : implode( ' ', [
                    isset( $argument['key_a'] ) ? $argument['key_a'] : $argument['key'],
                    isset( $argument['operator'] ) ? $argument['operator'] : ( $arg_value === null && ! isset( $argument['key_b'] ) ? 'is' : '=' ),
                    isset( $argument['key_b'] )
                        ? $argument['key_b']
                        : ( is_array( $arg_value )
                            ? ( '(\'' . implode( '\',\'', $arg_value ) . '\')' )
                            : ( $arg_value === null
                                ? 'null'
                                : $wpdb->prepare( is_numeric( $arg_value ) ? '%d' : '%s' , $arg_value )
                            )
                        ),
                ] );
            $join['on'][] = [
                'joint'     => isset( $argument['joint'] ) ? $argument['joint'] : 'AND',
                'condition' => $statement,
            ];
        }
        $this->builder['join'][] = $join;
        return $this;
    }
    /**
     * Adds limit statement.
     * @since 1.0.0
     * 
     * @param int $limit
     * 
     * @return \TenQuality\WP\Database\QueryBuilder this for chaining.
     */
    public function limit( $limit )
    {
        $this->builder['limit'] = $limit;
        return $this;
    }
    /**
     * Adds offset statement.
     * @since 1.0.0
     * 
     * @param int $offset
     * 
     * @return \TenQuality\WP\Database\QueryBuilder this for chaining.
     */
    public function offset( $offset )
    {
        $this->builder['offset'] = $offset;
        return $this;
    }
    /**
     * Adds order by statement.
     * @since 1.0.0
     * 
     * @param string $key
     * @param string $direction
     * 
     * @return \TenQuality\WP\Database\QueryBuilder this for chaining.
     */
    public function order_by( $key, $direction = 'ASC' )
    {
        $direction = trim( strtoupper( $direction ) );
        if ( $direction !== 'ASC' && $direction !== 'DESC' )
            throw new Exception( 'Invalid direction value.', 10200 );
        if ( ! empty( $key ) )
            $this->builder['order'][] = $key . ' ' . $direction;
        return $this;
    }
    /**
     * Adds group by statement.
     * @since 1.0.0
     * 
     * @param string $statement
     * 
     * @return \TenQuality\WP\Database\Utility\QueryBuilder this for chaining.
     */
    public function group_by( $statement )
    {
        if ( ! empty( $statement ) )
            $this->builder['group'][] = $statement;
        return $this;
    }
    /**
     * Adds having statement.
     * @since 1.0.0
     * 
     * @param string $statement
     * 
     * @return \TenQuality\WP\Database\QueryBuilder this for chaining.
     */
    public function having( $statement )
    {
        if ( ! empty( $statement ) )
            $this->builder['having'] = $statement;
        return $this;
    }
    /**
     * Retunrs results from builder statements.
     * @since 1.0.0
     * 
     * @global object $wpdb
     * 
     * @param int      $output           WPDB output type.
     * @param callable $callable_mapping Function callable to filter or map results to.
     * 
     * @return array
     */
    public function get( $output = OBJECT, $callable_mapping = null )
    {
        global $wpdb;
        $this->builder = apply_filters( 'query_builder_get_builder', $this->builder );
        $this->builder = apply_filters( 'query_builder_get_builder_' . $this->id, $this->builder );
        // Build
        // Query
        $query = '';
        $this->_query_select( $query );
        $this->_query_from( $query );
        $this->_query_join( $query );
        $this->_query_where( $query );
        $this->_query_group( $query );
        $this->_query_having( $query );
        $this->_query_order( $query );
        $this->_query_limit( $query );
        $this->_query_offset( $query );
        // Process
        $query = apply_filters( 'query_builder_get_query', $query );
        $query = apply_filters( 'query_builder_get_query_' . $this->id, $query );
        $results = $wpdb->get_results( $query, $output );
        if ( $callable_mapping ) {
            $results = array_map( function( $row ) use( &$callable_mapping ) {
                return call_user_func_array( $callable_mapping, [$row] );
            }, $results );
        }
        return $results;
    }
    /**
     * Returns first row found.
     * @since 1.0.0
     * 
     * @global object $wpdb
     * 
     * @param int $output WPDB output type.
     * 
     * @return object|array
     */
    public function first( $output = OBJECT )
    {
        global $wpdb;
        $this->builder = apply_filters( 'query_builder_first_builder', $this->builder );
        $this->builder = apply_filters( 'query_builder_first_builder_' . $this->id, $this->builder );
        // Build
        // Query
        $query = '';
        $this->_query_select( $query );
        $this->_query_from( $query );
        $this->_query_join( $query );
        $this->_query_where( $query );
        $this->_query_group( $query );
        $this->_query_having( $query );
        $this->_query_order( $query );
        $query .= ' LIMIT 1';
        $this->_query_offset( $query );
        // Process
        $query = apply_filters( 'query_builder_first_query', $query );
        $query = apply_filters( 'query_builder_first_query_' . $this->id, $query );
        return $wpdb->get_row( $query, $output );
    }
    /**
     * Returns a value.
     * @since 1.0.0
     * 
     * @global object $wpdb
     * 
     * @param int $x Column of value to return. Indexed from 0.
     * @param int $y Row of value to return. Indexed from 0.
     * 
     * @return mixed
     */
    public function value( $x = 0, $y = 0 )
    {
        global $wpdb;
        $this->builder = apply_filters( 'query_builder_value_builder', $this->builder );
        $this->builder = apply_filters( 'query_builder_value_builder_' . $this->id, $this->builder );
        // Build
        // Query
        $query = '';
        $this->_query_select( $query );
        $this->_query_from( $query );
        $this->_query_join( $query );
        $this->_query_where( $query );
        $this->_query_group( $query );
        $this->_query_having( $query );
        $this->_query_order( $query );
        $this->_query_limit( $query );
        $this->_query_offset( $query );
        // Process
        $query = apply_filters( 'query_builder_value_query', $query );
        $query = apply_filters( 'query_builder_value_query_' . $this->id, $query );
        return $wpdb->get_var( $query, $x, $y );
    }
    /**
     * Returns the count.
     * @since 1.0.0
     * 
     * @global object $wpdb
     * 
     * @param string|int $column       Count column.
     * @param bool       $bypass_limit Flag that indicates if limit + offset should be considered on count.
     * 
     * @return int
     */
    public function count( $column = 1, $bypass_limit = true )
    {
        global $wpdb;
        $this->builder = apply_filters( 'query_builder_count_builder', $this->builder );
        $this->builder = apply_filters( 'query_builder_count_builder_' . $this->id, $this->builder );
        // Build
        // Query
        $query = 'SELECT count(' . $column . ') as `count`';
        $this->_query_from( $query );
        $this->_query_join( $query );
        $this->_query_where( $query );
        $this->_query_group( $query );
        $this->_query_having( $query );
        if ( ! $bypass_limit ) {
            $this->_query_limit( $query );
            $this->_query_offset( $query );
        }
        // Process
        $query = apply_filters( 'query_builder_count_query', $query );
        $query = apply_filters( 'query_builder_count_query_' . $this->id, $query );
        return intval( $wpdb->get_var( $query ) );
    }
    /**
     * Retunrs column results from builder statements.
     * @since 1.0.6
     * 
     * @global object $wpdb
     * 
     * @param int $x Column index number.
     * 
     * @return array
     */
    public function col( $x = 0 )
    {
        global $wpdb;
        $this->builder = apply_filters( 'query_builder_col_builder', $this->builder );
        $this->builder = apply_filters( 'query_builder_col_builder_' . $this->id, $this->builder );
        // Build
        // Query
        $query = '';
        $this->_query_select( $query );
        $this->_query_from( $query );
        $this->_query_join( $query );
        $this->_query_where( $query );
        $this->_query_group( $query );
        $this->_query_having( $query );
        $this->_query_order( $query );
        $this->_query_limit( $query );
        $this->_query_offset( $query );
        // Process
        $query = apply_filters( 'query_builder_col_query', $query );
        $query = apply_filters( 'query_builder_col_query_' . $this->id, $query );
        return $wpdb->get_col( $query, $x );
    }
    /**
     * Builds query's select statement.
     * @since 1.0.0
     * 
     * @param string &$query
     */
    private function _query_select( &$query )
    {
        $query = 'SELECT ' . ( is_array( $this->builder['select'] )
            ? implode( ',' , $this->builder['select'] )
            : $this->builder['select']
        );
    }
    /**
     * Builds query's from statement.
     * @since 1.0.0
     * 
     * @param string &$query
     */
    private function _query_from( &$query )
    {
        $query .= ' FROM ' . $this->builder['from'];
    }
    /**
     * Builds query's join statement.
     * @since 1.0.0
     * 
     * @param string &$query
     */
    private function _query_join( &$query )
    {
        foreach ( $this->builder['join'] as $join ) {
            $query .= ( $join['left'] ? ' LEFT JOIN ' : ' JOIN ' ) . $join['table'];
            for ( $i = 0; $i < count( $join['on'] ); ++$i ) {
                $query .= ( $i === 0 ? ' ON ' : ' ' . $join['on'][$i]['joint'] . ' ' )
                    . $join['on'][$i]['condition'];
            }
        }
    }
    /**
     * Builds query's where statement.
     * @since 1.0.0
     * 
     * @param string &$query
     */
    private function _query_where( &$query )
    {
        for ( $i = 0; $i < count( $this->builder['where'] ); ++$i ) {
            $query .= ( $i === 0 ? ' WHERE ' : ' ' . $this->builder['where'][$i]['joint'] . ' ' )
                . $this->builder['where'][$i]['condition'];
        }
    }
    /**
     * Builds query's group by statement.
     * @since 1.0.0
     * 
     * @param string &$query
     */
    private function _query_group( &$query )
    {
        if ( count( $this->builder['group'] ) )
            $query .= ' GROUP BY ' . implode( ',', $this->builder['group'] );
    }
    /**
     * Builds query's having statement.
     * @since 1.0.0
     * 
     * @param string &$query
     */
    private function _query_having( &$query )
    {
        if ( $this->builder['having'] )
            $query .= ' HAVING ' . $this->builder['having'];
    }
    /**
     * Builds query's order by statement.
     * @since 1.0.0
     * 
     * @param string &$query
     */
    private function _query_order( &$query )
    {
        if ( count( $this->builder['order'] ) )
            $query .= ' ORDER BY ' . implode( ',', $this->builder['order'] );
    }
    /**
     * Builds query's limit statement.
     * @since 1.0.0
     * 
     * @global object $wpdb
     * 
     * @param string &$query
     */
    private function _query_limit( &$query )
    {
        global $wpdb;
        if ( $this->builder['limit'] )
            $query .= $wpdb->prepare( ' LIMIT %d', $this->builder['limit'] );
    }
    /**
     * Builds query's offset statement.
     * @since 1.0.0
     * 
     * @global object $wpdb
     * 
     * @param string &$query
     */
    private function _query_offset( &$query )
    {
        global $wpdb;
        if ( $this->builder['offset'] )
            $query .= $wpdb->prepare( ' OFFSET %d', $this->builder['offset'] );
    }
    /**
     * Sanitize value.
     * @since 1.0.0
     * 
     * @param string|bool $callback Sanitize callback.
     * @param mixed       $value
     * 
     * @return mixed
     */
    private function sanitize_value( $callback, $value )
    {
        if ( $callback === true )
            $callback = ( is_numeric( $value ) && strpos( $value, '.' ) !== false )
                ? 'floatval'
                : ( is_numeric( $value )
                    ? 'absint'
                    : ( is_string( $value )
                        ? 'sanitize_text_field'
                        : null
                    )
                );
        if ( strpos( $callback, '_builder' ) !== false )
            $callback = [&$this, $callback];
        if ( is_array( $value ) )
            for ( $i = count( $value ) -1; $i >= 0; --$i ) {
                $value[$i] = $this->sanitize_value( true, $value[$i] );
            }
        return $callback && is_callable( $callback ) ? call_user_func_array( $callback, [$value] ) : $value;
    }
    /**
     * Returns value escaped with WPDB `esc_like`,
     * @since 1.0.6
     * 
     * @param mixed $value
     * 
     * @return string
     */
    private function _builder_esc_like( $value )
    {
        global $wpdb;
        return $wpdb->esc_like( $value );
    }
    /**
     * Returns escaped value for LIKE comparison and appends wild card at the beggining.
     * @since 1.0.6
     * 
     * @param mixed $value
     * 
     * @return string
     */
    private function _builder_esc_like_wild_value( $value )
    {
        return '%' . $this->_builder_esc_like( $value );
    }
    /**
     * Returns escaped value for LIKE comparison and appends wild card at the end.
     * @since 1.0.6
     * 
     * @param mixed $value
     * 
     * @return string
     */
    private function _builder_esc_like_value_wild( $value )
    {
        return $this->_builder_esc_like( $value ) . '%';
    }
    /**
     * Returns escaped value for LIKE comparison and appends wild cards at both ends.
     * @since 1.0.6
     * 
     * @param mixed $value
     * 
     * @return string
     */
    private function _builder_esc_like_wild_wild( $value )
    {
        return '%' . $this->_builder_esc_like( $value ) . '%';
    }
}