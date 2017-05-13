<?php namespace Octommerce\Promo\Listeners;

use Prosehat\Wallet\Models\Voucher;
use Prosehat\Wallet\Validation\Voucher\Classes\Validator;
use Prosehat\Wallet\Validation\Campaign\Classes\Validator as CampaignValidator;

class ResetCouponOnCart
{

    public function handle($cartHelper, $product, $cart)
    {
        $cart->coupon_code = null;
        $cart->discount_information = null;
        $cart->discount = 0;
    }

}
