<?php namespace Octommerce\Promo\Rules;

use Carbon\Carbon;
use Octommerce\Promo\Classes\RuleBase;
use Octommerce\Octommerce\Models\Product;

class Products extends RuleBase
{
    public $error_message = 'Product is not suitable.';

    public $options = [
    ];

	public function ruleDetails()
    {
        return [
            'name'        => 'Products',
            'code'        => 'products',
            'description' => 'Check the products.',
        ];
    }

    public function registerProperties()
    {
    	return [
    		'product' => [
                'label'           => 'Product',
                'type'            => 'dropdown',
                'options'         => $this->getProductsOptions(),
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
        return in_array($options['product_ids'], $this->props['products']);
    }

    protected function getProductsOptions()
    {
        // return Product::lists('name', 'id');
        return Product::select('name', 'id')->get()->pluck('name', 'id');
    }

}