<?php namespace Octommerce\Promo\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddCouponFieldsOnOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('octommerce_octommerce_orders', function(Blueprint $table) {
            $table->integer('coupon_redemption_id')->unsigned()->nullable()->after('discount');
            $table->string('coupon_code')->nullable()->after('discount');
        });
    }

    public function down()
    {
        Schema::table('octommerce_octommerce_orders', function(Blueprint $table) {
            $table->dropColumn('coupon_code');
            $table->dropColumn('coupon_redemption_id');
        });
    }
}