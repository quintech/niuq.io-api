<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdFontesMediaTable extends Migration
{
    public function up()
    {
        Schema::create('ad_fontes_media', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->text('source'); // Source
            $table->text('domain_url'); // Source URL
            $table->text('bias');
            $table->text('reliability');
            $table->text('bias_label');
            $table->text('reliability_label');
            $table->text('media_type');
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
        Schema::dropIfExists('ad_fontes_media');
    }
}
