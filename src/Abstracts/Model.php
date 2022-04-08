<?php

namespace TenQuality\WP\Database\Abstracts;

use TenQuality\WP\Database\Abstracts\DataModel;
use TenQuality\WP\Database\Traits\DataModelTrait;

class Model extends DataModel
{
    use DataModelTrait;

    /**
     * Update data by ID
     * @param int $id
     * @param array $data
     * @return bool 
     */
    public static function updateData($id, $data)
    {
        $model = self::find(Request::input('id'));
        return $model->update($data);
    }
}
