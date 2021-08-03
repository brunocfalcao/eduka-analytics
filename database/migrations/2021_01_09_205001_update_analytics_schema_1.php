<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAnalyticsSchema1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visitor_goals', function (Blueprint $table) {
            if (! Schema::hasColumn('visitor_goals', 'course_id')) {
                $table->foreignId('course_id')
                      ->comment('The related course id, if exists')
                      ->nullable()
                      ->after('attributes');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
