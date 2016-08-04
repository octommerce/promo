<?php namespace Octommerce\Promo\Models;

use Db;
use Model;

/**
 * Model
 */
class CouponRedemption extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Validation
     */
    public $rules = [
        'coupon_code' => 'required',
    ];

    public $jsonable = ['data'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'octommerce_promo_coupon_redemptions';

    public $belongsTo = [
        'coupon' => 'Octommerce\Promo\Models\Coupon',
        'user' => 'RainLab\User\Models\User',
    ];

    public function redeem()
    {
        $this->status = 'success';
        $this->save();
    }

    public function release()
    {
        // If already released
        if( $this->status == 'expired') {
            return;
        }

        try {
            Db::beginTransaction();

            $this->status = 'expired';
            $this->save();

            $coupon = $this->coupon;
            $coupon->stock += $this->amount;
            $coupon->save();

            Db::commit();
        }
        catch(\Exception $e) {
            Db::rollBack();
        }
    }
}