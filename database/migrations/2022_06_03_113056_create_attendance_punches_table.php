<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancePunchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_punches', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Attendance::class);
            $table->time('time');
            $table->string('latitude');
            $table->string('longitude');
            $table->enum('punch_type', ['check-in', 'check-out']);
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
        Schema::dropIfExists('attendance_punches');
    }
}
