<?php
/**
 * WP mockery class.
 * @version 1.0.0
 */
class WPDB
{
    /**
     * Last query executed.
     * @since 1.0.0
     * @var string
     */
    public static $query = '';
    /**
     * Last table used.
     * @since 1.0.0
     * @var string
     */
    public static $table = '';
    public $prefix = 'prefix_';
    public $insert_id = 0;
    public function prepare()
    {
        $args = func_get_args();
        $query = $args[0];
        return $query;
    }
    public function get_results( $query, $output = OBJECT )
    {
        static::$query = $query;
        $results = [];
        for ( $i = 0; $i < 4; $i++ ) {
            $obj = new stdClass;
            $obj->model_id = $i + 1;
            $obj->type = 'type';
            $obj->name = uniqid();
            $obj->time = time();
            $obj->date = date( 'Y-m-d H:i' );
            $results[] = $output === ARRAY_A
                ? get_object_vars( $obj )
                : $obj;
        }
        return $results;
    }
    public function get_row( $query, $output = OBJECT )
    {
        static::$query = $query;
        $obj = new stdClass;
        $obj->model_id = 101;
        $obj->type = 'type';
        $obj->name = uniqid();
        $obj->time = time();
        $obj->date = date( 'Y-m-d H:i' );
        return  $output === ARRAY_A
            ? get_object_vars( $obj )
            : $obj;
    }
    public function get_var( $query, $x = 0, $y = 0 )
    {
        $value = null;
        $data = $this->get_results( $query );
        if ( count( $data ) > $y ) {
            $x_count = 0;
            foreach ( $data[$y] as $key => $x_value ) {
                if ( $x_count === $x )
                    return $x_value;
                $x_count++;
            }
        }
        return null;
    }
    public function insert( $table )
    {
        static::$table = $table;
        $this->insert_id = 99;
        return true;
    }
    public function update( $table )
    {
        static::$table = $table;
        return true;
    }
    public function delete( $table )
    {
        static::$table = $table;
        return true;
    }
    public function get_query()
    {
        return static::$query;
    }
    public function get_table()
    {
        return static::$table;
    }
}