<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->text('title');
            $table->text('url');
            $table->integer('count');
            $table->uuid('ad_fontes_media_uuid');
            $table->uuid('media_bias_fact_check_uuid');
            $table->text('fact_mata_context');
            $table->float('reliability',9,4);
            $table->float('factual_reporting',3,0);
            $table->float('fakeness',9,4);
            


            // Add created_at and updated_at columns
            $table->timestamps();
            // Soft delete
            $table->softDeletes();

            // Set primary key
            $table->primary('uuid');
            // Set foreign key constraints to prevent deletion if there are child records
            $table->foreign('ad_fontes_media_uuid')->references('uuid')->on('ad_fontes_media')->onDelete('RESTRICT');
            $table->foreign('media_bias_fact_check_uuid')->references('uuid')->on('media_bias_fact_check')->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::dropIfExists('news');
    }
}
