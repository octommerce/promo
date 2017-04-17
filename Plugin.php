<?php namespace Octommerce\Promo;

use Event;
use System\Classes\PluginBase;
use Octommerce\Promo\Models\Coupon;
use Octommerce\Promo\Classes\PromoManager;
use Octommerce\Promo\Classes\Validator;

class Plugin extends PluginBase
{
    public $require = ['Octommerce.Octommerce'];

    public function registerComponents()
    {
    	return [
    		'Octommerce\Promo\Components\CouponValidator' => 'couponValidator',
            'Octommerce\Promo\Components\PromoDetail'     => 'promoDetail',
    	];
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
    	$promoManager = PromoManager::instance();

    	//
    	// Built in Validators
    	//

    	$promoManager->registerRules([
            'Octommerce\Promo\Rules\Products',
            'Octommerce\Promo\Rules\Brands',
            'Octommerce\Promo\Rules\Subtotal',
        ]);

        \Octommerce\Promo\Controllers\Promos::extendFormFields(function($form, $model, $context) use($promoManager) {
            if (!$model instanceof \Octommerce\Promo\Models\Rule)
                return;

            $promoManager->addRuleFields($form);

        });

        Event::listen('order.afterCreate', function($order, $data) {
            $code = isset($data['code']) ? trim($data['code']) : '';

            if (!$code)
                return;

            $validator = Validator::instance();

            $options = [
                'products' => $order->products,
            ];

            $target = [
                'subtotal' => $order->subtotal,
            ];

            $count = 1;

            // Validate
            if($validator->validate($code, $options, $target, $count)) {

                // Get the coupon
                $coupon = Coupon::find($validator->output['coupon_id']);

                // Hold the coupon based on determined amount
                $redemption = $coupon->hold($order->user_id, $count);

                $order->coupon_code = $coupon->code;
                $order->coupon_redemption_id = $redemption->id;

                if (isset($validator->output['target']['subtotal'])) {
                    $order->discount = $validator->output['target']['subtotal'];
                }

                $order->save();

            }
        });

        // Extend Order backend list
        Event::listen('backend.list.extendColumns', function($widget) {

            // Only for the Order controller
            if (!$widget->getController() instanceof \Octommerce\Octommerce\Controllers\Orders) {
                return;
            }

            // Only for the Order model
            if (!$widget->model instanceof \Octommerce\Octommerce\Models\Order) {
                return;
            }

            $widget->addColumns(
                [
                    'coupon_code' => [
                        'label'      => 'Promo Code',
                        'type'       => 'text',
                        'sortable'   => false,
                        'invisible'  => true,
                        'searchable' => true
                    ]
                ]
            );
        });


        // Extend Order backend filter
        Event::listen('backend.filter.extendScopes', function($widget) {

            // Only for the Order controller
            if (!$widget->getController() instanceof \Octommerce\Octommerce\Controllers\Orders) {
                return;
            }

            $widget->addScopes(
                [
                    'coupon_code' => [
                        'label'      => 'Is Promotion',
                        'type'       => 'checkbox',
                        'conditions' => 'coupon_code <> "" or coupon_code IS NOT NULL',
                    ]
                ]
            );
        });

    }
}
