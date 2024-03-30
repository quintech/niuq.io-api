<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->uuid('google_user_uuid');
            $table->uuid('news_uuid');
            $table->char('transaction_hex',66);
            $table->char('niuq_erc20_quantity',21);

            // Add created_at and updated_at columns
            $table->timestamps();
            // Soft delete usage
            $table->softDeletes();

            // Set primary key
            $table->primary('uuid');
            $table->foreign('news_uuid')->references('uuid')->on('news')->onDelete('RESTRICT');
            $table->foreign('google_user_uuid')->references('uuid')->on('google_user')->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction');
    }
};
