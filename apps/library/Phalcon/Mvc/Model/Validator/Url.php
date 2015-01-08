<?php
namespace Phalcon\Mvc\Model\Validator;

use Phalcon\Mvc\Model\Validator,
    Phalcon\Mvc\Model\ValidatorInterface;

class Url extends Validator implements ValidatorInterface
{

    public function validate($model)
    {
        $field = $this->getOption('field');

        $value = $model->$field;
        $filtered = filter_var($value, FILTER_VALIDATE_URL);
        if (!$filtered) {
            $this->appendMessage("The URL is invalid", $field, "Validator_Url");
            return false;
        }
        return true;
    }

}
