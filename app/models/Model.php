<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    /**
     * 重写paginate方法
     *
     * @param integer $integer
     * @param array   $columns
     * @param boolean $justItems 是否只返回items
     *
     * @return mixed
     */
    public static function paginate($perPage, $columns = array('*'), $offset = null)
    {
        if (empty($this)) {
            $model = new static;
        }

        if (is_null($offset)) {
            $currentPage = empty($_GET['page']) ? 1 : abs($_GET['page']);
        }

        $offset = ($currentPage - 1) * $perPage;

        return $model->getQuery()->skip($offset)->select($columns)->take($perPage)->get();
    }
}