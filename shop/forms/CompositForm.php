<?php

namespace shop\forms;

use yii\base\Model;
use yii\helpers\ArrayHelper;

abstract class CompositForm extends Model
{
    /**
     * @var Model[]
     */
    private $forms = [];

    abstract protected function internalForms(): array;

    public function load($data, $formName = null): bool
    {
        $success = parent::load($data, $formName);
        foreach ($this->forms as $name => $item) {
            if(is_array($item)) {
                foreach ($item as $itemName => $itemForm) {
                    $success = $this->loadInternal($data, $itemForm, $formName, $itemName) && $success;
                }
            } else {
                $success = $this->loadInternal($data, $item, $formName, $name) && $success;
            }
        }
        return $success;
    }

    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        $parentNames = array_filter($attributeNames, 'is_string');
        $success = parent::validate($parentNames, $clearErrors);
        foreach ($this->forms as $name => $item) {
            if(is_array($item)) {
                foreach ($item as $itemName => $itemForm) {
                    $innerNames = ArrayHelper::getValue($attributeNames, $itemName);
                    $success = $itemForm->validate($innerNames, $clearErrors) && $success;
                }
            } else {
                $innerNames = ArrayHelper::getValue($attributeNames, $name);
                $success = $item->validate($innerNames, $clearErrors) && $success;
            }
        }
        return $success;
    }

    public function __get($name)
    {
        if(isset($this->forms[$name])) {
            return $this->forms[$name];
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if(in_array($name, $this->internalForms(), true)) {
            $this->forms[$name] = $value;
        }
        parent::__set($name, $value);
    }

    public function __isset($name)
    {
        return isset($this->forms[$name]) || parent::__isset($name);
    }

    private function loadInternal(array $data, Model $form, $formName, $name): bool
    {
        return $form->load($data, $formName ? null : $name);
    }
}