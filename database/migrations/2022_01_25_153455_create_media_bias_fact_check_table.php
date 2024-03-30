<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaBiasFactCheckTable extends Migration
{
    public function up()
    {
        Schema::create('media_bias_fact_check', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->text('web_name');
            $table->text('url');
            $table->text('context');

            // Add created_at and updated_at columns
            $table->timestamps();
            // Soft delete
            $table->softDeletes();

            // Set primary key
            $table->primary('uuid');
        });
    }

    public function down()
    {
        Schema::dropIfExists('media_bias_fact_check');
    }
}
