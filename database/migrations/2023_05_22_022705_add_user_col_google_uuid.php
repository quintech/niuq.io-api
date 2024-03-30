<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserColGoogleUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->text('email')->nullable()->change();            //Alternate verification email
            $table->string('owner_name', 15)->nullable()->change(); // Display name for the website
            $table->uuid('google_uuid')->nullable();
            
            $table->text('wallet_account')->nullable();             // Wallet account blockchain account number
            $table->boolean('gender')->nullable();                  //Gender: True for male, false for female
            $table->date('birthday')->nullable();                   //Birthday
            $table->text('country')->nullable();                    //Country
            $table->text('state')->nullable();                      //City
            $table->text('zip')->nullable();                        //ZIP code
            $table->text('address')->nullable();                    //address

            $table->foreign('google_uuid')->references('uuid')->on('google_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user',function(Blueprint $table){
            $table->dropForeign('user_google_uuid_foreign');
            $table->dropColumn('google_uuid');
            $table->dropColumn('wallet_account');
            $table->dropColumn('gender');
            $table->dropColumn('birthday');
            $table->dropColumn('country');
            $table->dropColumn('state');
            $table->dropColumn('zip');
            $table->dropColumn('address');
        });
    }
}
