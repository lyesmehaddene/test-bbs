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
            $table->string('english_name')->unique();
            $table->boolean('is_planet');
            $table->json('moons');
            $table->integer('semimajor_axis');
            $table->integer('perihelion');
            $table->integer('aphelion');
            $table->decimal('eccentricity');
            $table->decimal('inclination');
            $table->json('masses');
            $table->json('vol');
            $table->decimal('density');
            $table->decimal('gravity');
            $table->decimal('escape');
            $table->integer('mean_radius');
            $table->integer('equa_radius');
            $table->integer('polar_radius');
            $table->string('dimension');
            $table->decimal('sideral_orbit');
            $table->decimal('sideral_rotation');
            $table->json('around_planet');
            $table->string('discovered_by');
            $table->string('discovery_date');
            $table->string('alternative_name');
            $table->decimal('axial_tilt');
            $table->integer('avg_temp');
            $table->decimal('main_anomaly');
            $table->decimal('arg_periapsis');
            $table->decimal('long_asc_node');
            $table->string('body_type');
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
