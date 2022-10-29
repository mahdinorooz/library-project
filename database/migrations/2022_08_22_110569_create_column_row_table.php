<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnRowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('column_row', function (Blueprint $table) {
            $table->id();
            $table->foreignId('column_id')->constrained('columns')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('row_id')->constrained('rows')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('type_id')->nullable()->constrained('types')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['column_id', 'row_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('column_row');
    }
}
