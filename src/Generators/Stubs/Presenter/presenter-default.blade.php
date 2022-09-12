@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

use Devesharp\Patterns\Presenter\Presenter;

class {{ $resourceName }}Presenter extends Presenter {
//    public function fullName()
//    {
//        return $this->first_name . ' ' . $this->last_name;
//    }
}
