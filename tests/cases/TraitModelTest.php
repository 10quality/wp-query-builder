<?php

use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.13
 */
class TraitModelTest extends TestCase
{
    /**
     * Test abstract
     * @since 1.0.0
     * @group model
     * @group trait
     */
    public function testFind()
    {
        // Prepare
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
     * @group model
     * @group trait
     */
    public function testFindWhere()
    {
        // Prepare
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
     * @group model
     * @group trait
     */
    public function testInsert()
    {
        // Prepare
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
     * @group model
     * @group trait
     */
    public function testWhere()
    {
        // Exec
        $collection = Model::where( ['name' => 'yolo'] );
        // Assert
        $this->assertIsArray( $collection );
        $this->assertInstanceOf( 'Model', $collection[0] );
    }
    /**
     * Test abstract
     * @since 1.0.0
     * @group model
     * @group trait
     */
    public function testCount()
    {
        // Exec
        $count = Model::count();
        // Assert
        $this->assertIsInt( $count );
        $this->assertEquals( 1, $count );
    }
    /**
     * Test abstract
     * @since 1.0.7
     * @group model
     * @group trait
     */
    public function testAll()
    {
        // Prepare
        global $wpdb;
        // Exec
        $collection = Model::all();
        // Assert
        $this->assertIsArray( $collection );
        $this->assertInstanceOf( 'Model', $collection[0] );
    }
    /**
     * Test abstract
     * @since 1.0.12
     * @group model
     * @group trait
     * @group update
     */
    public function testUpdate()
    {
        // Prepare
        global $wpdb;
        // Exec
        $flag = Model::update_all( ['status' => 'active'] );
        // Assert
        $this->assertIsBool( $flag );
        $this->assertTrue( $flag );
        $this->assertEquals(
            'UPDATE prefix_' . Model::TABLE . ' SET status = %s',
            $wpdb->get_query()
        );
    }
    /**
     * Test abstract
     * @since 1.0.12
     * @group model
     * @group trait
     * @group update
     */
    public function testUpdateWhere()
    {
        // Prepare
        global $wpdb;
        // Exec
        $flag = Model::update_all( ['status' => 'active'], ['type' => 'yolo'] );
        // Assert
        $this->assertEquals(
            'UPDATE prefix_' . Model::TABLE . ' SET status = %s WHERE type = %s',
            $wpdb->get_query()
        );
    }
}