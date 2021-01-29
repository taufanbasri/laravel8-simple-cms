<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class LivewireCustomCrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:livewire-crud 
    {nameOfTheClass? : The name of the class.}, 
    {nameOfTheModelClass? : The name of the model class.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a custom Livewire CRUD';

    /**
     * Our custom class properties here!
     */
    protected $nameOfTheClass;
    protected $nameOfTheModelClass;
    protected $file;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->file = new Filesystem();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Gathers all parameters
        $this->gatherParameters();

        // Generates the Livewire Class File
        $this->generateLivewireCrudClassFile();

        // Generates the Livewire View File
        $this->generateLivewireCrudViewFile();
    }

    protected function gatherParameters()
    {
        $this->nameOfTheClass = $this->argument('nameOfTheClass');
        $this->nameOfTheModelClass = $this->argument('nameOfTheModelClass');

        // If you didn't input the name of the class
        if (!$this->nameOfTheClass) {
            $this->nameOfTheClass = $this->ask('Enter class name');
        }

        // If you didn't input the name of the model class
        if (!$this->nameOfTheModelClass) {
            $this->nameOfTheModelClass = $this->ask('Enter model name');
        }

        // Convert to studly case
        $this->nameOfTheClass = Str::studly($this->nameOfTheClass);
        $this->nameOfTheModelClass = Str::studly($this->nameOfTheModelClass);
    }

    protected function generateLivewireCrudClassFile()
    {
        // Set the origin & destination for the livewire class file.
        $fileOrigin = base_path('/stubs/custom.livewire.crud.stub');
        $fileDestination = base_path('/app/Http/Livewire/' . $this->nameOfTheClass . '.php');

        if ($this->file->exists($fileDestination)) {
            $this->info('This class file already exists: ' . $this->nameOfTheClass . '.php');
            $this->info('Aborting class file creation.');
            return false;
        }

        // Get the original string content of the file.
        $fileOriginalString = $this->file->get($fileOrigin);

        $replaceFileOriginalString = Str::replaceArray('{{}}', [
            $this->nameOfTheModelClass,
            $this->nameOfTheClass,
            $this->nameOfTheModelClass,
            $this->nameOfTheModelClass,
            $this->nameOfTheModelClass,
            $this->nameOfTheModelClass,
            $this->nameOfTheModelClass,
            Str::kebab($this->nameOfTheClass),
        ], $fileOriginalString);

        // Put the content into the destination directory.
        $this->file->put($fileDestination, $replaceFileOriginalString);

        $this->info('Livewire class file created: ' . $fileDestination);
    }

    protected function generateLivewireCrudViewFile()
    {
        // Set the origin & destination for the livewire class file.
        $fileOrigin = base_path('/stubs/custom.livewire.crud.view.stub');
        $fileDestination = base_path('/resources/views/livewire/' . Str::kebab($this->nameOfTheClass) . '.blade.php');

        if ($this->file->exists($fileDestination)) {
            $this->info('This view file already exists: ' . Str::kebab($this->nameOfTheClass) . '.blade.php');
            $this->info('Aborting view file creation.');
            return false;
        }

        // Copy file to destination
        $this->file->copy($fileOrigin, $fileDestination);

        $this->info('Livewire view file created: ' . $fileDestination);
    }
}
