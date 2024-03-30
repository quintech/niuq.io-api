<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleUserTable extends Migration
{
    public function up()
    {
        Schema::create('google_user', function (Blueprint $table) {
            $table->uuid('uuid');          //quin's own uuid
            $table->char('google_id', 21); //record of the id returned by Google
            $table->text('owner_name');    //name displayed on the website
            $table->text('user_img_url');  //user image URL
            $table->text('email');

            $table->char('wallet_account',42)->nullable(true);        //wallet account blockchain account number
            $table->boolean('gender')->nullable(true);      //gender True for male; false for female
            $table->date('birthday')->nullable(true);       //birthday
            $table->text('country')->nullable(true);        //country
            $table->text('state')->nullable(true);          //city
            $table->text('zip')->nullable(true);            //postal code
            $table->text('address')->nullable(true);        //residential address
            $table->boolean('registered');                        //check if non-required information has been filled

            //add created_at and updated_at fields
            $table->timestamps();
            //soft delete
            $table->softDeletes();

            //set primary key
            $table->primary('uuid');
        });
    }

    public function down()
    {
        Schema::dropIfExists('google_user');
    }
}
