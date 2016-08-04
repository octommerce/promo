<?php namespace Octommerce\Promo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBigmangoPromoCoupons extends Migration
{
    public function up()
    {
        Schema::create('octommerce_promo_coupons', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->string('code');
            $table->integer('stock')->nullable()->unsigned()->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->integer('promo_id')->nullable()->unsigned();
            $table->integer('usage_limit')->nullable()->unsigned()->default(0);
            $table->integer('usage_limit_interval')->nullable()->unsigned();
            $table->enum('usage_limit_interval_unit', ['minute', 'hour', 'day', 'week', 'month', 'year'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('octommerce_promo_coupons');
    }
}