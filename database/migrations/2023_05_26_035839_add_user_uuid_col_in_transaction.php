<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserUuidColInTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->uuid('google_user_uuid')->nullable()->change();
            $table->uuid('user_uuid')->after('google_user_uuid')->nullable();
            $table->foreign('user_uuid')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->dropForeign('transaction_user_uuid_foreign');
            $table->dropColumn('user_uuid');
            $table->uuid('google_user_uuid')->change();
        });
    }
}
