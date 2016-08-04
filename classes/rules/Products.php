<?php namespace Octommerce\Promo\Classes\Rules;

use Carbon\Carbon;
use Octommerce\Promo\Classes\RuleBase;
use Octommerce\Octommerce\Models\Product;

class Products extends RuleBase
{
    public $error_message = 'Product is not suitable.';

    public $options = [
        'product_id' => 'required',
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
    		'products' => [
    			'label'    => 'Products',
    			'type'     => 'checkboxList',
                'options'  => $this->getProductsOptions(),
    			'required' => true,
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
        return in_array($options['product_id'], $this->props['products']);
    }

    protected function getProductsOptions()
    {
        return Product::lists('name', 'id');
    }

}