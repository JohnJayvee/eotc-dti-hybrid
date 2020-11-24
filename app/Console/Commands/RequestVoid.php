<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Laravel\Models\RegionalOffice;

class RequestVoid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:requestvoid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $request = new RegionalOffice();
        $request->name = "dsadsa";
        $request->save();
    }
}
