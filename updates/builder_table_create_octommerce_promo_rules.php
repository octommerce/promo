<?php namespace Octommerce\Promo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateBigmangoPromoRules extends Migration
{
    public function up()
    {
        Schema::create('octommerce_promo_rules', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('promo_id')->unsigned()->nullable();
            $table->string('type');
            $table->text('options')->nullable();
            $table->text('error_message')->nullable();
            $table->integer('sort_order')->nullable()->unsigned();
            $table->enum('operand', ['and', 'or', 'xor'])->nullable()->default('and');
            $table->text('description')->nullable();
            $table->string('output_type')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('octommerce_promo_rules');
    }
}