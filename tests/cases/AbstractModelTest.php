<?php

/**
 * Test.
 *
 * @author Local Vibes <https://localvibes.co/> Hyper Tribal
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.1
 */
class AbstractModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test abstract
     * @since 1.0.0
    */
    public function testTablenameProperty()
    {
        // Preapre
        global $wpdb;
        $model = new Model;
        // Assert
        $this->assertEquals( $wpdb->prefix . Model::TABLE, $model->tablename );
    }
    /**
     * Test abstract
     * @since 1.0.0
     */
    public function testSave()
    {
        // Preapre
        global $wpdb;
        $model = new Model( [
            'name'  => 'test',
        ] );
        // Exec
        $flag = $model->save();
        // Assert
        $this->assertTrue( $flag );
        $this->assertEquals( $wpdb->prefix . Model::TABLE, $wpdb->get_table() );
        $this->assertEquals( 99, $model->model_id );
    }
    /**
     * Test abstract
     * @since 1.0.1
     */
    public function testSaveUpdate()
    {
        // Preapre
        global $wpdb;
        $model = new Model( [
            'model_id'  => 888999,
            'name'      => 'test',
        ] );
        // Exec
        $flag = $model->save();
        // Assert
        $this->assertTrue( $flag );
        $this->assertEquals( $wpdb->prefix . Model::TABLE, $wpdb->get_table() );
        $this->assertEquals( 888999, $model->model_id );
    }
    /**
     * Test abstract
     * @since 1.0.1
     */
    public function testSaveForceInsert()
    {
        // Preapre
        global $wpdb;
        $model = new Model( [
            'model_id'  => 888999,
            'name'      => 'test',
        ] );
        // Exec
        $flag = $model->save( true );
        // Assert
        $this->assertTrue( $flag );
        $this->assertEquals( 99, $model->model_id );
    }
    /**
     * Test abstract
     * @since 1.0.0
     */
    public function testDelete()
    {
        // Preapre
        global $wpdb;
        $model = new Model( [
            'model_id'  => 99,
        ] );
        // Exec
        $flag = $model->delete();
        // Assert
        $this->assertTrue( $flag );
        $this->assertEquals( $wpdb->prefix . Model::TABLE, $wpdb->get_table() );
    }
    /**
     * Test abstract
     * @since 1.0.0
     */
    public function testDeleteEmpty()
    {
        // Preapre
        $model = new Model();
        // Exec
        $flag = $model->delete();
        // Assert
        $this->assertFalse( $flag );
    }
    /**
     * Test abstract
     * @since 1.0.0
     */
    public function testLoad()
    {
        // Preapre
        global $wpdb;
        $model = new Model( [
            'model_id'  => 101,
        ] );
        // Exec
        $model->load();
        // Assert
        $this->assertEquals(
            'SELECT * FROM prefix_' . Model::TABLE . ' WHERE model_id = %d LIMIT 1',
            $wpdb->get_query()
        );
        $this->assertEquals( 101, $model->model_id );
        $this->assertEquals( 'type', $model->type );
    }
}