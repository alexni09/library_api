<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class AttribAdmin extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:attrib-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gives admin status to the user specified by its {email}.';

    /**
     * Execute the console command.
     */
    public function handle() {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        if (!isset($user)) {
            $this->error('The specified email was not found in the users table.');
            $this->newline();
            $this->line('Failed.');
        } else {
            $user->is_admin = true;
            $user->save();
            $this->info('Success!');
        }
    }
}