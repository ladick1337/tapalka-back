<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Role;
use App\Notifications\TestNotification;
use Illuminate\Console\Command;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

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
     * @return int
     */
    public function handle()
    {

//        for($i = 0; $i < 100; $i++){
//            Role::create(['name' => 'Test role ' . $i, 'permissions' => []]);
//        }
//
//        for($i = 0; $i < 100; $i++){
//           Admin::factory()->credentials(rand(), rand())->role(rand(1, 50))->create();
//        }

        $admin = Admin::where('login', 'root')->first();

        $admin->notify(new TestNotification('dias dakjs jkdasjkd asjkdjk asjkd jksjk daskjdkjasdkjaskjdkjasjkdakjdsjkasdkjkjdsack'));

        return 0;
    }
}
