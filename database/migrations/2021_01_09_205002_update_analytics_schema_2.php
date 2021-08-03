<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAnalyticsSchema2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('visitor_goals', 'goals');

        Schema::table('visits', function (Blueprint $table) {
            $table->renameColumn('goal_achieved', 'goal_id');
        });

        Schema::table('goals', function (Blueprint $table) {
            $table->dropForeign('visitor_goals_visitor_id_foreign');
            $table->dropColumn('visitor_id');

            $table->string('name')
                  ->comment('The goal name, compact description. E.g.: "first visit"')
                  ->change();

            $table->string('description')
                  ->comment('If necessary it shows a more detailed goal description')
                  ->change();

            if (! Schema::hasColumn('goals', 'course_id')) {
                $table->foreignId('course_id')
                      ->comment('The related course id, if exists, if null then the goal is on the backend')
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
