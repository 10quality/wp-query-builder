<?php

/**
 * Test.
 *
 * @author Local Vibes <https://localvibes.co/> Hyper Tribal
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.0
 */
class TraitModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test abstract
     * @since 1.0.0
     */
    public function testFind()
    {
        // Preapre
        global $wpdb;
        // Exec
        $model = Model::find( 101 );
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_' . Model::TABLE . ' WHERE model_id = %d LIMIT 1',
            $wpdb->get_query()
        );
        $this->assertEquals( 101, $model->model_id );
        $this->assertEquals( 'type', $model->type );
    }
    /**
     * Test abstract
     * @since 1.0.0
     */
    public function testFindWhere()
    {
        // Preapre
        global $wpdb;
        // Exec
        $model = Model::find_where( ['name' => 'yolo'] );
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_' . Model::TABLE . ' WHERE name = %s LIMIT 1',
            $wpdb->get_query()
        );
        $this->assertEquals( 101, $model->model_id );
    }
    /**
     * Test abstract
     * @since 1.0.0
     */
    public function testInsert()
    {
        // Preapre
        global $wpdb;
        // Exec
        $model = Model::insert( ['name' => 'yolo'] );
        // Assert
        $this->assertEquals( $wpdb->prefix . Model::TABLE, $wpdb->get_table() );
        $this->assertEquals( 99, $model->model_id );
        $this->assertEquals( 'yolo', $model->name );
    }
    /**
     * Test abstract
     * @since 1.0.0
     */
    public function testWhere()
    {
        // Exec
        $collection = Model::where( ['name' => 'yolo'] );
        // Assert
        $this->assertInternalType( 'array', $collection );
        $this->assertInstanceOf( 'Model', $collection[0] );
    }
    /**
     * Test abstract
     * @since 1.0.0
     */
    public function testCount()
    {
        // Exec
        $count = Model::count();
        // Assert
        $this->assertInternalType( 'int', $count );
        $this->assertEquals( 1, $count );
    }
}