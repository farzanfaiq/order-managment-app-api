<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Item extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('categroy_id');
            $table->unsignedBigInteger('subcategroy_id')->nullable();
            $table->boolean('variation')->default(0);
            $table->enum('item_type', ['veg', 'non-veg']);
            $table->integer('price');
            $table->string('image')->nullable();
            $table->integer('tax')->default(0);
            $table->enum('tax_type', ['percentage', 'fixed']);
            $table->integer('discount')->default(0); 
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->string('description');
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('user_id');
            
            $table->foreign('categroy_id')->references('id')->on('categories')->onDelete('cascade'); 
            $table->foreign('subcategroy_id')->references('id')->on('categories')->onDelete('cascade'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('items');
    }
}
