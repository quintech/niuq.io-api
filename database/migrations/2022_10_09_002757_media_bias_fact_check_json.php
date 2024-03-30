<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MediaBiasFactCheckJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_bias_fact_check_json', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->text('name');
            $table->text('b')->nullable(true);
            $table->text('d')->nullable(true);
            $table->text('f')->nullable(true);
            $table->text('n')->nullable(true);
            $table->text('r')->nullable(true);
            $table->text('u')->nullable(true);
            $table->text('p')->nullable(true);
            $table->text('c')->nullable(true);
            $table->text('a')->nullable(true);
            $table->text('q')->nullable(true);

            // Add created_at and updated_at columns
            $table->timestamps();
            // Soft delete usage
            $table->softDeletes();

            // Set primary key
            $table->primary('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_bias_fact_check_json');
    }
}
