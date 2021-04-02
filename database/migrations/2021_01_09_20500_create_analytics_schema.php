<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyticsSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ip_addresses', function (Blueprint $table) {
            $table->id();

            $table->ipAddress('ip_address');

            $table->boolean('is_blacklisted')
                  ->default(false);

            $table->boolean('is_throttled')
                  ->default(false);

            $table->unsignedBigInteger('hits')
                  ->default(1);

            $table->engine = 'InnoDB';
            $table->timestamps();
        });

        Schema::create('visitors', function (Blueprint $table) {
            $table->id();

            $table->string('hash') // GDPR reasons.
                  ->nullable()
                  ->unique();

            $table->string('continent')
                  ->nullable();

            $table->string('continentCode')
                  ->nullable();

            $table->string('country')
                  ->nullable();

            $table->string('countryCode')
                  ->nullable();

            $table->string('region')
                  ->nullable();

            $table->string('regionName')
                  ->nullable();

            $table->string('city')
                  ->nullable();

            $table->string('district')
                  ->nullable();

            $table->string('zip')
                  ->nullable();

            $table->decimal('latitude', 11, 7)
                  ->nullable();

            $table->decimal('longitude', 11, 7)
                  ->nullable();

            $table->string('timezone')
                  ->nullable();

            $table->string('currency')
                  ->nullable();

            $table->engine = 'InnoDB';
            $table->timestamps();
        });

        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visitor_id')
                  ->nullable()
                  ->constrained();

            $table->string('path')
                  ->comment('The url path, not including the full url');

            $table->string('referrer')
                  ->comment('The referrer, if exists');

            $table->string('base_referrer')
                  ->comment('If an HTTP REFERER exists this is the full url path')
                  ->nullable();

            $table->string('campaign')
                  ->comment('If there is a querystring with the name ?cmpgn=xxx or ?utm_source=xxx then it is recorded here')
                  ->nullable();

            $table->engine = 'InnoDB';
            $table->timestamps();
        });

        /*
         * Visitor goals:
         * nth-time: A visitor that visited, more than once, the website.
         * purchase-click: A visitor that clicked on the purchase.
         * purchased-completed: A visitor that completed the purchase.
         * purchase-abandoned: A visitor that abandoned the purchase.
         * from-promotion: A visitor that refered from a promotional campaign.
         * from-referal: A visitor that refered from another named referal.
         * refunded: A visitor that requested a refund.
         *
         * The visitor gates are built on custom classes that will be loaded
         * by the GateTracing middleware. Each time there is a new routing
         * being hit, it will reload all the gate classes and trigger the
         * necessary database logging.
         **/
        Schema::create('visitor_goals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visitor_id')
                  ->constrained();

            $table->string('name');

            $table->text('description')
                  ->nullable();

            $table->longText('attributes')
                  ->comment('Extra attribute for the saved goal')
                  ->nullable();

            $table->engine = 'InnoDB';
            $table->timestamps();
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
