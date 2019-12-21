<?php

use Illuminate\Database\Migrations\Migration;
use App\User;

/**
 * Create admin user
 */
class CreateAdmin extends Migration
{
    public function up()
    {
        $user = new User();
        $user->email = $this->getEmail();
        $user->name = 'Admin';
        $user->setPassword('admin');
        $user->is_admin = true;
        $user->save();
    }

    public function down()
    {
        User::where('email', $this->getEmail())->delete();
    }

    /**
     * @return string
     */
    private function getEmail(): string
    {
        return config('pizza.adminEmail');
    }
}
