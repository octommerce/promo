<?php namespace Octommerce\Promo\Controllers;

use Flash;
use Request;
use ApplicationException;
use Backend\Classes\Controller;
use BackendMenu;
use Octommerce\Promo\Classes\Validator;
use October\Rain\Parse\Ini;

class Simulator extends Controller
{
    public $implement = [];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Octommerce.Promo', 'promo', 'simulator');
    }

    public function index()
    {
    	$this->pageTitle = 'Simulator';

    	$this->addCss('/plugins/turez/commerce/assets/css/jsontree.css');
        $this->addJs('/plugins/turez/commerce/assets/js/jsontree.min.js');
    }

    public function onCheck()
	{
		$code = trim(Request::input('code'));
	    if (! $code)
	        throw new ApplicationException('Please input code');

	    $iniParser = new Ini;
	    $options = $iniParser->parse(Request::input('options'));
        $target = $iniParser->parse(Request::input('target'));
        $count = Request::input('count');

	    $validator = Validator::instance();

        // Set simulation to true
        $validator->isSimulation = true;

	    // Validate
        if($validator->validate($code, $options, $target, $count)) {

		    $this->vars['output'] = [
                'type' => $validator->outputType,
                'output' => $validator->output,
            ];

            Flash::success('Coupon is valid!');

		    return [
		        'partialContents' => $this->makePartial('output')
		    ];
        } else {
            // return error message

            Flash::error($validator->error_message);

            $this->vars['output'] = [];

            return [
                'partialContents' => $this->makePartial('output')
            ];
        }
	}
}