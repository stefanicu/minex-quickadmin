<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\BufferedOutput;

class FredhImportMinex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'freshimport:minex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from minex-live table with backup images. This table is need to be updated before to lunch in production.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->output = new BufferedOutput() ;//BufferedOutput;

        $this->call('bkp:Media');
        $clientid = $this->output->fetch();

        $this->call('migrate:fresh');
        $clientsecret = $this->output->fetch();

        $this->call('db:seed');
        $clientsecret = $this->output->fetch();

        $this->call('import:minex');
        $clientsecret = $this->output->fetch();

    }
}
