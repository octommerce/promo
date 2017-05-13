<?php namespace Octommerce\Promo\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class AddCouponFieldsOnCartsTable extends Migration
{
    public function up()
    {
        Schema::table('octommerce_octommerce_carts', function(Blueprint $table) {
            $table->string('coupon_code')->nullable()->after('discount');
        });
    }

    public function down()
    {
        Schema::table('octommerce_octommerce_carts', function(Blueprint $table) {
            $table->dropColumn('coupon_code');
        });
    }
}
