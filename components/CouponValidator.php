<?php namespace Octommerce\Promo\Components;

use Cart;
use Flash;
use Cms\Classes\ComponentBase;
use Octommerce\Promo\Classes\Validator;

class CouponValidator extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'couponValidator Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'input_name' => [
                'title'       => 'Input Name',
                'description' => 'Input name for code at your form.',
                'default'     => 'code',
                'type'        => 'string'
            ],
        ];
    }

    public function onCheck()
    {
        $validator = Validator::instance();

        $this->page['cart'] = $cart = Cart::get();

        // Get input code
        $code = trim(post($this->property('input_name')));
        if (! $code) {

            if ($cart->coupon_code) {
                $this->clearCoupon($cart);
                return;
            }

            throw new \ApplicationException('Please fill the code');
        }


        // Built-in options
        $options = [
            'products' => $cart->products,
        ];

        // Get options from input
        $options = array_merge($options, is_array(post('options')) ? post('options') : []);

        // Get target
        $target = post('target');

        $target['subtotal'] = $cart->total_price;

        // Get count
        // $count = post('count') ?: 1;
        $count = 1; // Jika minta lebih, dikasih stock nya aja berapa

        // Validate
        if($validator->validate($code, $options, $target, $count)) {

            $cart->discount = isset($validator->output['target']['subtotal']) ? $validator->output['target']['subtotal'] : 0;
            $cart->coupon_code = $validator->output['coupon_code'];

            // return success message
            Flash::success($validator->output['message']);
        } else {
            $this->clearCoupon($cart);
            // return error message
            Flash::error($validator->error_message);
            // \Flash::error($validator->error_message);
            // return false;
        }

        $cart->save();
    }

    public function onClear()
    {
        $this->page['cart'] = $cart = Cart::get();

        $this->clearCoupon($cart);

        $cart->save();

        return $cart;
    }

    protected function clearCoupon($cart)
    {
        $cart->discount = 0;
        $cart->coupon_code = null;
        Flash::info('Coupon cleared');
    }
}
