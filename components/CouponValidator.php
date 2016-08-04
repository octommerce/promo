<?php namespace Octommerce\Promo\Components;

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

        // Get input options
        $options = post('options');

        // Get target
        $target = post('target');

        // Get count
        // $count = post('count') ?: 1;
        $count = 1; // Jika minta lebih, dikasih stock nya aja berapa

        // Validate
        if($validator->validate($code, $options, $target, $count)) {

            // return success message
            return $validator->output;
        } else {
            // return error message
            throw new \ApplicationException($validator->error_message);
            // \Flash::error($validator->error_message);
            // return false;
        }
    }

}