<?php

namespace Devesharp\Support;

trait ModelGetTable
{
    static function getTableName($column = null): string {
        if (empty($column)) {
            return (new self())->getTable();
        } else {
            return (new self())->getTable() . '.' . $column;
        }
    }
}
