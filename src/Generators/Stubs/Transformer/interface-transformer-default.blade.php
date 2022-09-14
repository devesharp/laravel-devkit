@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

enum {{ $resourceName }}TransformerType
{
    case default;
}
