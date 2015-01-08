<?php
namespace Phalcon\Mvc\Model\Validator;

use Phalcon\Mvc\Model\Validator,
    Phalcon\Mvc\Model\ValidatorInterface;

class Phone extends Validator implements ValidatorInterface
{
	public function validate($model)
    {
        $field = $this->getOption('field');
        $lengths = (int)$this->getOption('lengths');
        $message = $this->getOption('message');
		
		if (!is_array($lengths)) {
			if ($lengths <= 0 ) {
				$lengths = 11;
			}
			$lengths = array($lengths);
		}
		
		$value = $model->$field;
		$value = preg_replace('/\D+/', '', $value);

		$len = strlen($value);

		if (!in_array($len, $lengths)) {
			$this->appendMessage($message ? $message : "The Phone is invalid", $field, "Validator_Phone");
			return false;
		}
        
        return true;
    }
}
