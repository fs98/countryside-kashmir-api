<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->string('name', 32);
            $table->string('email', 128);
            $table->string('phone_number', 128);
            $table->string('address', 128);
            $table->string('city', 128);
            $table->string('country', 128);

            $table->smallInteger('persons', false, true);
            $table->smallInteger('adults', false, true);
            $table->smallInteger('children', false, true);

            $table->timestamp('arrival_date');

            $table->smallInteger('days', false, true);
            $table->smallInteger('night', false, true);

            $table->foreignId('package_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

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
        Schema::dropIfExists('bookings');
    }
};
