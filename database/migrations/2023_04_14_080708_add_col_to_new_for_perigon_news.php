<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToNewForPerigonNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('author')->nullable()->after('title')->comment('Author');
            $table->text('description')->nullable()->after('author')->comment('Description');
            $table->text('image')->nullable()->after('addDate')->comment('Image');
            $table->string('from')->nullable()->after('image')->comment('News Source');
            $table->date('addDate')->nullable()->after('description')->comment('Add Date');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('author');
            $table->dropColumn('description');
            $table->dropColumn('addDate');
            $table->dropColumn('image');
            $table->dropColumn('from');
        });
    }
}
