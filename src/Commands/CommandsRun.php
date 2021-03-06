<?php

namespace Zakhayko\CommandManager\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class CommandsRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commands:run {group?} {--t|test-mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs Command Manager.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $class = config('command-manager.manager_class');
        if (!$class || !class_exists($class)) {
            $this->error('Manager class does not exists!');
            return;
        }
        $options = [];
        if ($this->option('test-mode')) $options['test_mode'] = true;
        if ($this->hasArgument('group')) $options['group'] = $this->argument('group');
        (new $class)->run($options, $this);
        return;
    }

    public function line($string, $style = null, $verbosity = null)
    {
        if ($style === 'warning' && !$this->output->getFormatter()->hasStyle('warning')) {
            $newStyle = new OutputFormatterStyle('yellow');
            $this->output->getFormatter()->setStyle('warning', $newStyle);
        }
        elseif ($style === 'danger' && !$this->output->getFormatter()->hasStyle('danger')) {
            $newStyle = new OutputFormatterStyle('red');
            $this->output->getFormatter()->setStyle('danger', $newStyle);
        }
        parent::line($string, $style, $verbosity);
    }
}
