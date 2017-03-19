<?php

namespace App\Services\Core\Generator;

use File;
use Storage;

class Generator
{
    private $path;
    private $moduleDirs = [
        'Controllers', 'Exceptions', 'Models', 'Requests', 'Routes', 'Tasks', 'Transformers'
    ];
    private $module;
    private $routeApiFile = 'routes/api.php';

    function __construct()
    {
        $this->path = app_path('Modules');
    }

    private function makeDir($folderName)
    {
        $folderPath = $this->path. '/' . $folderName;
        if( ! is_dir($folderPath))
            mkdir($folderPath);

        return $folderPath;
    }

    private function makeFile($folderName, $fileName, $contents)
    {
        $folderPath = $this->makeDir($folderName);
        $filePath = $folderPath . '/' . $fileName . '.php';
        if( ! is_file($filePath))
            File::put($filePath, $contents);
    }

    private function getContentsByType($type)
    {
        return file_get_contents(app_path('Services/Core/Generator/Stubs') . '/' . $type . '.stub');
    }

    private function replaceStubContent($stub, $data)
    {
        $collection = collect($data);
        $collection = $collection->mapWithKeys(function ($item, $key) {
            return ['{{'.$key.'}}' => $item];
        });

        return str_replace($collection->keys()->all(), $collection->values()->all(), $stub);
    }

    private function prettyName($module)
    {
        return ucfirst( strtolower($module) );
    }

    public function modules()
    {
        $directories = Storage::directories($this->path);

        return $directories;
    }

    public function moduleExist($module)
    {
        $this->module = $module;

        return in_array($this->prettyName($module), $this->modules());
    }

    public function makeModule($module)
    {
        $module = $this->prettyName($module);

        if($this->moduleExist($module))
            return $module;

        $this->makeDir($module);

        foreach ($this->moduleDirs as $dir)
        {
            mkdir($this->path . '/' . $module . '/' . $dir);
        }
        $this->insertRoute($module);
        $this->updateRoute($module);

        return $module;
    }

    private function insertRoute($module)
    {
        $this->makeFile($module . '/Routes', 'routes', "<?php\n");
    }

    private function updateRoute($module)
    {
        $apiRoute = "\n\n" . 'Route::namespace(\'Modules\{{Module}}\Controllers\')->group(RoutePath::get(\'{{Module}}\'));';
        $newModuleRoute = str_replace('{{Module}}', $module, $apiRoute);

        File::append(base_path($this->routeApiFile), $newModuleRoute);
    }

    public function routeForController($name)
    {
        $routeStr = "\n\n" . 'Route::get(\'/{{Route}}\', \'{{Name}}Controller\');';
        $route = str_replace(
            ['{{Route}}', '{{Name}}'],
            [strtolower($name), $name],
            $routeStr
        );

        File::append($this->path . '/Routes/routes.php', $route);
    }

    public function modelData($table)
    {
        $columns = GeneratorDataFromDB::getAllColumnsNames($table);

        $dates = [];
        $fillable = [];
        $properties = [];
        foreach($columns as $colName => $colType)
        {
            if($colType == 'timestamp')
                $dates[] = "\t\t'$colName'";
            else
                $fillable[] = "\t\t'$colName'";

            $properties[] = ' * @property mixed ' . $colName;
        }
        $data['fillable'] = implode(','."\n", $fillable);
        $data['dates'] = implode(','."\n", $dates);
        $data['table'] = 'protected $table = \'' . $table . '\';';
        $data['properties'] = implode("\n", $properties);

        return $data;
    }

    public function transformerData($table)
    {
        $columns = GeneratorDataFromDB::getAllColumnsNames($table);

        $transformers = [];
        $apiDocs = [];
        foreach ($columns as $colName => $colType)
        {
            $type = '';
            if($colType == 'int')
                $type = '(int)';

            $transformers[] = "\t\t\t" . '\'' . $colName . '\' => ' . $type . '$model->' . $colName;
            $type = ($colType == 'int') ? 'integer' : 'string';
            $apiDocs[] = '* @SWG\Property(property="' . $colName . '", type="' . $type .'"),';
        }

        $data['properties'] = implode(',' . "\n", $transformers);
        $data['properties_docs'] = implode("\n\t\t", $apiDocs);

        return $data;
    }

    public function generateByType($type, $name, $addData = [])
    {
        $type = $this->prettyName($type);

        if( ! in_array($type, $this->moduleDirs))
        {
            dd('Type not found!');
            return false;
        }

        $this->path .= '/' . $this->module;
        $data = [
            'file_name' => $name,
            'module_name' => $this->module
        ];

        $contents = $this->getContentsByType($type);

        if( ! empty($data))
            $data = array_merge($data, $addData);
        $contents = $this->replaceStubContent($contents, $data);

        if($type == 'Models')
            $fullName = $name;
        else
            $fullName = $name . str_singular($type);

        $this->makeFile($type, $fullName, $contents);

        return $fullName;
    }
}

