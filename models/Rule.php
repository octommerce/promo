<?php namespace Octommerce\Promo\Models;

use Model;
use Octommerce\Promo\Classes\PromoManager;

/**
 * Model
 */
class Rule extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /*
     * Validation
     */
    public $rules = [
        'type' => 'required',
        'operand' => 'required',
    ];

    public $jsonable = ['options'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'octommerce_promo_rules';

    public $belongsTo = [
        'promo' => 'Octommerce\Promo\Models\Promo',
    ];

    public function getTypeOptions()
    {
        $promoManager = PromoManager::instance();

        $list = [];
        foreach($promoManager->rules as $rule) {
            $list[$rule['code']] = $rule['name'];
        }

        return $list;
    }

    public function beforeSave()
    {
        $this->options = [
            $this->type => isset($this->options[$this->type]) ? $this->options[$this->type] : null
        ];
    }
}