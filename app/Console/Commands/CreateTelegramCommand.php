<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class CreateTelegramCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:telegram-command {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Telegram Command';

    protected $type = 'Telegram command';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path() . '/stubs/telegram.command.stub';
    }
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Telegram\Commands';
    }
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
    }
}
