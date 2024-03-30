<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->uuid('uuid');             //quin's own uuid
            $table->text('email');            //Alternate email for verification
            $table->string('account', 20);    //Account for quick login
            $table->string('password', 60);   // Will be encrypted, so length is extended
            $table->string('owner_name', 15); //Name displayed on the website

            // Add created_at and updated_at columns
            $table->timestamps();
            // Soft delete usage
            $table->softDeletes();

            // Set primary key
            $table->primary('uuid');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user');
    }
}
