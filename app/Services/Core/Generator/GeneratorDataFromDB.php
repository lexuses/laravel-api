<?php

namespace App\Services\Core\Generator;

use DB;

class GeneratorDataFromDB
{
    public static function allTables()
    {
        return collect(DB::select('show tables'))->map(function ($item){
            return $item->{'Tables_in_'.env('DB_DATABASE')};
        })->all();
    }

    public static function tableExist($table)
    {
        $tables = self::allTables();
        return in_array($table, $tables);
    }

    public static function getAllColumnsNames($table, $columns_search = null)
    {
        switch (DB::connection()->getConfig('driver'))
        {
            case 'pgsql':
                $query = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "'";
                $column_name = 'column_name';
                $column_type = 'data_type';
                $reverse = true;
                break;

            case 'mysql':
                $query = 'SHOW COLUMNS FROM ' . $table;
                $column_name = 'Field';
                $column_type = 'Type';
                $reverse = false;
                break;

            case 'sqlsrv':
                $parts = explode('.', $table);
                $num = (count($parts) - 1);
                $table = $parts[$num];
                $query = "SELECT column_name FROM " . DB::connection()->getConfig('database') . ".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'" . $table . "'";
                $column_name = 'column_name';
                $column_type = 'Type';
                $reverse = false;
                break;

            default:
                $error = 'Database driver not supported: ' . DB::connection()->getConfig('driver');
                throw new Exception($error);
                break;
        }

        $columns = array();

        foreach (DB::select($query) as $column)
        {
            $type = $column->$column_type;
            if ( ! $columns_search OR ($columns_search AND in_array($column->$column_name, $columns_search)))
                $columns[$column->$column_name] = preg_replace('|(\(.*)|', '', $type); // setting the column name as key too
        }

        if ($reverse)
        {
            $columns = array_reverse($columns);
        }

        return $columns;
    }

    public static function getModelFillable($moduleName, $model)
    {
        if( !$model)
            return false;

        $modelNamespace = '\App\Modules\\' . $moduleName . '\Models\\' . $model;
        if ( ! class_exists($modelNamespace))
            return false;


        $model = new $modelNamespace;
        $properties = self::getAllColumnsNames($model->getTable(), $model->getFillable());

        return $properties;
    }
}