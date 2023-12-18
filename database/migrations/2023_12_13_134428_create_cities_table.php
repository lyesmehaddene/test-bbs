<?php

use App\Models\Department;
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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('insee_city_code')->unique();
            $table->string('postal_city_name')->nullable();
            $table->string('postal_code');
            $table->string('acheminement_name')->nullable();
            $table->string('line_5')->nullable();
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->string('city_code');
            $table->string('article')->nullable();
            $table->string('city_name')->nullable();
            $table->string('city_full_name');
            $table->foreignIdFor(Department::class)->constrained();
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
        Schema::dropIfExists('cities');
    }
};
