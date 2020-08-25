<?php

use PHPUnit\Framework\TestCase;

/**
 * Test.
 *
 * @author 10 Quality <info@10quality.com>
 * @license MIT
 * @package wp-query-builder
 * @version 1.0.12
 */
class AbstractModelTest extends TestCase
{
    /**
     * Test abstract
     * @since 1.0.0
     * @group abstract
     * @group model
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
     * @group abstract
     * @group model
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
     * @group abstract
     * @group model
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
     * @group abstract
     * @group model
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
     * @group abstract
     * @group model
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
     * @group abstract
     * @group model
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
     * @group abstract
     * @group model
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
    /**
     * Test abstract
     * @since 1.0.7
     * @group abstract
     * @group model
     */
    public function testSaveTimestamps()
    {
        // Preapre
        global $wpdb;
        $model = new Model( [
            'name'  => 'test',
        ] );
        $date_format = 'Y-m-d H:i:s';
        // Exec
        $flag = $model->save();
        $created_at = DateTime::createFromFormat( $date_format, $model->created_at );
        $updated_at = DateTime::createFromFormat( $date_format, $model->updated_at );
        // Assert
        $this->assertTrue( $flag );
        $this->assertNotEmpty( $model->created_at );
        $this->assertNotEmpty( $model->updated_at );
        $this->assertTrue( $created_at && $created_at->format( $date_format ) === $model->created_at );
        $this->assertTrue( $updated_at && $updated_at->format( $date_format ) === $model->updated_at );
    }
    /**
     * Test abstract
     * @since 1.0.12
     * @group abstract
     * @group model
     * @group update
     */
    public function testUpdate()
    {
        // Preapre
        global $wpdb;
        $model = new Model( [
            'model_id'  => 888999,
            'name'  => 'test',
        ] );
        // Exec
        $flag = $model->update( ['name' => 'updated'] );
        // Assert
        $this->assertTrue( $flag );
        $this->assertEquals( 'updated', $model->name );
    }
}