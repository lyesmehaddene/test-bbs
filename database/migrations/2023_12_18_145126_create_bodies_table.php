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
        Schema::create('bodies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('english_name');
            $table->boolean('is_planet');
            $table->json('moons')->nullable();
            $table->integer('semimajor_axis')->nullable();
            $table->integer('perihelion')->nullable();
            $table->integer('aphelion')->nullable();
            $table->decimal('eccentricity')->nullable();
            $table->decimal('inclination')->nullable();
            $table->json('masses')->nullable();
            $table->json('vol')->nullable();
            $table->decimal('density')->nullable();
            $table->decimal('gravity')->nullable();
            $table->decimal('escape')->nullable();
            $table->integer('mean_radius')->nullable();
            $table->integer('equa_radius')->nullable();
            $table->integer('polar_radius')->nullable();
            $table->string('dimension')->nullable();
            $table->decimal('sideral_orbit')->nullable();
            $table->decimal('sideral_rotation')->nullable();
            $table->decimal('flattening')->nullable();
            $table->json('around_planet')->nullable();
            $table->string('discovered_by')->nullable();
            $table->string('discovery_date')->nullable();
            $table->string('rel')->nullable();
            $table->string('alternative_name')->nullable();
            $table->decimal('axial_tilt')->nullable();
            $table->integer('avg_temp')->nullable();
            $table->decimal('main_anomaly')->nullable();
            $table->decimal('arg_periapsis')->nullable();
            $table->decimal('long_asc_node')->nullable();
            $table->string('body_type')->nullable();
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
        Schema::dropIfExists('bodies');
    }
};
