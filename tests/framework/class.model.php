<?php

use TenQuality\WP\Database\Abstracts\DataModel;
use TenQuality\WP\Database\Traits\DataModelTrait;

class Model extends DataModel
{
    use DataModelTrait;
    /**
     * Data table name in database (without prefix).
     * @var string
     */
    const TABLE = 'model_table';
    /**
     * Data table name in database (without prefix).
     * @var string
     */
    protected $table = self::TABLE;
    /**
     * Primary key column name.
     * @var string
     */
    protected $primary_key = 'model_id';
    /**
     * Model properties.
     * @var string
     */
    protected $attributes = [
        'model_id',
        'test',
    ];
}