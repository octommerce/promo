<?php namespace Octommerce\Promo\Rules;

use Octommerce\Promo\Classes\RuleBase;

class Subtotal extends RuleBase
{
    public $options = [
    ];

	public function ruleDetails()
    {
        return [
            'name'        => 'Subtotal',
            'code'        => 'subtotal',
            'description' => 'Check the subtotal amount',
        ];
    }

    public function registerProperties()
    {
    	return [
    		'min' => [
    			'label'    => 'Min',
    			'type'     => 'number',
                'default'  => 0,
    			'required' => true,
    		],
            'max' => [
                'label'    => 'Max',
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
        if($target['subtotal'] < $this->props['min']) {
            $this->error_message = 'Minimum ' . $this->props['min'];
            return false;
        }

        if($this->props['max'] && $target['subtotal'] > $this->props['max']) {
            $this->error_message = 'Maximum ' . $this->props['max'];
            return false;
        }

        return true;
    }

}