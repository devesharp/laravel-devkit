<?php

namespace Devesharp\CRUD\DataInterface;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class DataInterface
{

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if(\property_exists($this, $key)) {
                $isDataInterface = false;

                // Resgata informações da variavel
                $rp = new \ReflectionProperty(get_class($this), $key);

                // Veriica se variavel é um DateInterface
                try {
                    $oReflectionClass = new \ReflectionClass($rp->getType()->getName());
                    $oReflectionClass->getParentClass();
                    $isDataInterface = $oReflectionClass->getParentClass()->getName() == DataInterface::class;
                }catch (\Exception $e) {}


                if($isDataInterface) {
                    $class = $rp->getType()->getName();
                    $this->{$key} = new $class($value);
                }else {
                    $this->{$key} = $value;
                }
            }
        }
    }

    function toArray() {
        $data = [];
        foreach (get_class_vars(get_class($this)) as $key => $value) {

            if (isset($this->$key)) {
                if($this->$key instanceof DataInterface) {
                    $data[$key] = $this->$key->toArray();
                }else {
                    $data[$key] = $this->$key;
                }
            }
        }

        return $data;
    }
}
