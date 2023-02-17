<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile_number');
            $table->foreignIdFor(\App\Models\Department::class);
            $table->foreignIdFor(\App\Models\Designation::class);
            $table->foreignIdFor(\App\Models\State::class);
            $table->foreignIdFor(\App\Models\City::class);
            $table->string('avatar')->nullable();
            $table->foreignId('manager_id');
            $table->enum('status', ['0', '1'])->default('1')->comment('0 => In-active, 1 =>  Active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
