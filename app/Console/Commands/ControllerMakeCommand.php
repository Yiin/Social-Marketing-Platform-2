<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use InvalidArgumentException;

class ControllerMakeCommand extends \Illuminate\Routing\Console\ControllerMakeCommand
{
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('model')) {
            return __DIR__ . '/../stubs/controller.model.stub';
        } elseif ($this->option('resource')) {
            return __DIR__ . '/../stubs/controller.stub';
        }

        return __DIR__ . '/../stubs/controller.plain.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Controllers';
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string $model
     * @return string
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (!Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace() . 'Models')) {
            $model = $rootNamespace . $model;
        }

        return $model;
    }
}
