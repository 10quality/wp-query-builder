<?php
/**
 * Wordpress mockery and framework needed functions and objects.
 * @version 1.0.0
 */
if ( ! defined( 'OBJECT' ) )
    define( 'OBJECT', 'OBJECT' );
if ( ! defined( 'ARRAY_A' ) )
    define( 'ARRAY_A', 'ARRAY_A' );
function apply_filters( $key, $value ) {
    return $value;
}
function do_action( $key ) {
    // Do Nothing
}
function absint( $value ) {
    return intval( $value );
}
function sanitize_text_field( $value ) {
    return $value;
}