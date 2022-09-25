@php
    echo "<?php".PHP_EOL;
@endphp

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('{{ $tableName }}', function (Blueprint $table) {
@if(!empty($fieldsMigration['fields']))
@foreach($fieldsMigration['fields'] as $field)
            {!! $field !!}
@endforeach
@endif

@if(!empty($fieldsMigration['fields']))
@foreach($fieldsMigration['foreignKeys'] as $field)
            {!! $field !!}
@endforeach
@endif
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{{ $tableName }}');
    }
};