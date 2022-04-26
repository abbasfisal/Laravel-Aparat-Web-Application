<?php


namespace App\Http\Requests;


trait GetRegisterFiledNamdAndFieldValueTrait
{

    public function getFieldName()
    {
        return $this->has('email') ? 'email' : 'mobile';

    }

    public function getFieldValue()
    {
        //get field name
        $fieldName = $this->getFieldName();

        //get field value
        $value = $this->get($fieldName);


        if ($fieldName === 'mobile') {
            $value = to_valid_mobile_number($value);
        }


        return $value;
    }
}
