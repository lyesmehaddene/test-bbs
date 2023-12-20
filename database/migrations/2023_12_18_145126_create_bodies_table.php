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
            $table->bigInteger('semimajor_axis')->nullable();
            $table->bigInteger('perihelion')->nullable();
            $table->bigInteger('aphelion')->nullable();
            $table->bigInteger('eccentricity')->nullable();
            $table->bigInteger('inclination')->nullable();
            $table->json('masses')->nullable();
            $table->json('vol')->nullable();
            $table->bigInteger('density')->nullable();
            $table->bigInteger('gravity')->nullable();
            $table->bigInteger('escape')->nullable();
            $table->bigInteger('mean_radius')->nullable();
            $table->bigInteger('equa_radius')->nullable();
            $table->bigInteger('polar_radius')->nullable();
            $table->string('dimension')->nullable();
            $table->bigInteger('sideral_orbit')->nullable();
            $table->bigInteger('sideral_rotation')->nullable();
            $table->decimal('flattening')->nullable();
            $table->json('around_planet')->nullable();
            $table->string('discovered_by')->nullable();
            $table->string('discovery_date')->nullable();
            $table->string('rel')->nullable();
            $table->string('alternative_name')->nullable();
            $table->bigInteger('axial_tilt')->nullable();
            $table->bigInteger('avg_temp')->nullable();
            $table->bigInteger('main_anomaly')->nullable();
            $table->bigInteger('arg_periapsis')->nullable();
            $table->bigInteger('long_asc_node')->nullable();
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
