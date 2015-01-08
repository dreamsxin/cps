<?php
namespace Phalcon\Mvc\Model\Validator;

use Phalcon\Mvc\Model\Validator,
    Phalcon\Mvc\Model\ValidatorInterface;

class IdCard extends Validator implements ValidatorInterface
{
	public function validate($model)
    {
        $field = $this->getOption('field');
		$message = $this->getOption('message');

        $value = $model->$field;
        
        if (!\IdCard::check($value)) {
            $this->appendMessage($message ? $message : "The Id Card is invalid", $field, "Validator_IdCard");
            return false;
        }
        return true;
    }
}
