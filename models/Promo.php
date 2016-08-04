<?php namespace Octommerce\Promo\Models;

use Model;
use Carbon\Carbon;

/**
 * Model
 */
class Promo extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
    ];

    public $dates = ['start_at', 'end_at'];

    public $jsonable = ['output'];

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'octommerce_promo_promos';

    public $hasMany = [
        'coupons' => 'Octommerce\Promo\Models\Coupon',
        'promo_rules' => 'Octommerce\Promo\Models\Rule', // Prevent conflict with `rules` for validation
    ];

    public $attachOne = [
        'image' => 'System\Models\File',
    ];

    public function getStatusAttribute()
    {
        $now = Carbon::now();

        if ($this->start_at > $now) {
            return 'waiting';
        } elseif ($this->end_at > $now) {
            return 'running';
        } else {
            return 'ended';
        }
    }
}