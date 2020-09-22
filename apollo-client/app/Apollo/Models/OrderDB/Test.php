<?php

namespace App\Apollo\Models\OrderDB;


use App\Orm;
use App\Apollo\Models\SoftDeletes;

class Test extends Orm
{
    use SoftDeletes; // 所有Model必须继承

    //默认的链接DB数据库；如果是分库分表操作必须保留此字段,设置为空
    protected $connection = 'db_test';

    //设置表名；如果是分库分表操作必须保留此字段,设置为空
    protected $table = 'test_tbl';

    //设置删除时间字段-可选
    const DELETED_AT = 'delete_time';
}