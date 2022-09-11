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
@foreach($fieldsMigration['fields'] as $field)
            {!! $field !!}
@endforeach

@foreach($fieldsMigration['foreignKeys'] as $field)
            {!! $field !!}
@endforeach
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