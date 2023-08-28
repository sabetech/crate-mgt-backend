<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'empties manager']);
        Role::create(['name' => 'operations manager']);
        Role::create(['name' => 'cashier']);
        Role::create(['name' => 'sales manager']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Role::where('name', 'admin')->delete();
        Role::where('name', 'empties manager')->delete();
        Role::where('name', 'operations manager')->delete();
        Role::where('name', 'cashier')->delete();
        Role::where('name', 'sales manager')->delete();

    }
};
