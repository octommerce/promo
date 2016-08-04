<?php namespace Octommerce\Promo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBigmangoPromoPromos extends Migration
{
    public function up()
    {
        Schema::create('octommerce_promo_promos', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('is_active')->default(1);
            $table->text('output')->nullable();
            $table->text('success_message')->nullable();
            $table->text('tnc')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('octommerce_promo_promos');
    }
}