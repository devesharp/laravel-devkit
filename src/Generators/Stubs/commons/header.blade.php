@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

@foreach($imports as $import)
use {{ $import }};
@endforeach