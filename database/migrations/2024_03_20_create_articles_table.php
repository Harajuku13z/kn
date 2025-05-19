<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('price_text');
            $table->string('image_path');
            $table->string('weight_text');
            $table->json('type')->default('[]');
            $table->json('usage')->default('[]');
            $table->string('weight_class');
            $table->decimal('average_weight', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
}; 