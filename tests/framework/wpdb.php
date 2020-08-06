<?php
/**
 * WP mockery class.
 * @version 1.0.11
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
     * Last query executed.
     * @since 1.0.0
     * @var string
     */
    public static $prepare_args = [];
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
        unset( $args[0] );
        foreach ( $args as $value) {
            if ( !in_array( $value, static::$prepare_args ) )
                static::$prepare_args[] = $value;
        }
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
    public function get_col( $query, $x = 0 )
    {
        static::$query = $query;
        $results = [];
        for ( $i = 0; $i < 4; $i++ ) {
            switch ( $x ) {
                case 1:
                    $results[] = 'type';
                    break;
                case 2:
                    $results[] = uniqid();
                    break;
                case 3:
                    $results[] = time();
                    break;
                case 4:
                    $results[] = date( 'Y-m-d H:i' );
                    break;
                case 0:
                default;
                    $results[] = $i + 1;
                    break;
            }
        }
        return $results;
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
    public function esc_like($val)
    {
        return 'esc_like('.$val.')';
    }
    public function filter_prepare_args($value)
    {
        return array_filter( static::$prepare_args, function($arg) use(&$value) {
            return $arg === $value;
        } );
    }
    public function query( $query )
    {
        static::$query = $query;
        return true;
    }
    public function get_prepare_args()
    {
        return static::$prepare_args;
    }
    public static function reset()
    {
        static::$prepare_args = [];
    }
}