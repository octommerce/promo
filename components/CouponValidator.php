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

        // Get input code
        $code = trim(post($this->property('input_name')));
        if( ! $code) {
            throw new \ApplicationException('Please fill the code');
        }

        $cart = Cart::get();

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

            $this->page['cart'] = $cart;

            // $this->page['discount'] = isset($validator->output['target']['subtotal']) ? $validator->output['target']['subtotal'] : 0;

            // $this->page['discount'] = 20000;

            // return success message
            Flash::success($validator->output['message']);
        } else {
            $cart->discount = 0;
            // return error message
            Flash::error($validator->error_message);
            // \Flash::error($validator->error_message);
            // return false;
        }

        $cart->save();

        $this->page['cart'] = $cart;
    }

}