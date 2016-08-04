<?php namespace Octommerce\Promo;

use Octommerce\Promo\Classes\PromoManager;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
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
            'Octommerce\Promo\Classes\Rules\Products',
            'Octommerce\Promo\Classes\Rules\Subtotal',
        ]);

        \Octommerce\Promo\Controllers\Promos::extendFormFields(function($form, $model, $context) use($promoManager) {
            if (!$model instanceof \Octommerce\Promo\Models\Rule)
                return;

            $promoManager->addRuleFields($form);

        });
    }
}
