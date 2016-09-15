<?php namespace Octommerce\Promo\Rules;

use Carbon\Carbon;
use Octommerce\Promo\Classes\RuleBase;
use Octommerce\Octommerce\Models\Brand;

class Brands extends RuleBase
{
    public $error_message = 'Product is not suitable.';

    public $options = [
    ];

	public function ruleDetails()
    {
        return [
            'name'        => 'Brands',
            'code'        => 'brands',
            'description' => 'Check the brands.',
        ];
    }

    public function registerProperties()
    {
    	return [
    		'brands' => [
                'label'           => 'Brands',
                'type'            => 'checkboxlist',
                'options'         => $this->getBrandsOptions(),
                'required'        => true,
                'span'            => 'full',
    		],
            'min_products' => [
                'label'    => 'Min. Products',
                'type'     => 'number',
            ],
            'max_products' => [
                'label'    => 'Max. Products',
                'type'     => 'number',
            ],
    	];
    }

    /**
     * On validate rule
     * @param  array $options [description]
     * @return boolean        [description]
     */
    public function onValidate($options, $target)
    {
        foreach ($options['products'] as $product) {
            // If not listed, return false
            if (! in_array($product->brand_id, $this->props['brands']))
                return false;
        }

        return true;
    }

    protected function getBrandsOptions()
    {
        return Brand::lists('name', 'id');
    }

}