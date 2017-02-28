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
    		'products' => [
                'label'           => 'Products',
                'type'            => 'checkboxlist',
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
        if (!isset($options['products']))
            return false;

        $products = $options['products'];

        foreach ($products as $product) {
            if (in_array($product->id, $this->props['products']))
                return true;
        }

        return false;
    }

    protected function getProductsOptions()
    {
        return Product::select('name', 'id')->orderBy('name', 'asc')->get()->pluck('name', 'id');
    }

}