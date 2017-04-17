<?php namespace Octommerce\Promo\Models;

use Db;
use Carbon\Carbon;
use Model;

/**
 * Model
 */
class Coupon extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Validation
     */
    public $rules = [
        'code' => 'required',
        // 'stock' => 'required',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'octommerce_promo_coupons';

    public $belongsTo = [
        'promo' => 'Octommerce\Promo\Models\Promo',
        'user' => 'RainLab\User\Models\User',
    ];

    public $hasMany = [
        'redemptions' => 'Octommerce\Promo\Models\CouponRedemption',
    ];

    /**
     * [hold description]
     * @param  [type]  $user_id    [description]
     * @param  integer $amount     [description]
     * @param  integer $time_limit [description]
     * @param  [type]  $data       [description]
     * @return [type]              [description]
     */
    public function hold($user_id, $amount = 1, $data = null)
    {
        try {
            Db::beginTransaction();

            if($this->stock < $amount) {
                return false;
            }

            $this->stock -= $amount;
            $this->save();

            $redemption              = new CouponRedemption;
            $redemption->user_id     = $user_id;
            $redemption->coupon_code = $this->code;
            $redemption->coupon_id   = $this->id;
            $redemption->amount      = $amount;
            $redemption->data        = $data;
            $redemption->status      = 'pending';
            // $redemption->expired_at  = Carbon::now()->addSeconds($time_limit);
            $redemption->save();

            Db::commit();

            return $redemption;
        }
        catch(\Exception $e) {
            Db::rollBack();

            throw new \ApplicationException($e->getMessage());

            return false;
        }
    }

    public function generate($promo_id, $amount = 1, $user = null, $options = [])
    {
        //
    }
}