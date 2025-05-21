@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};
@if(!empty($imports))

@foreach($imports as $import)
use {{ $import }};
@endforeach
@endif