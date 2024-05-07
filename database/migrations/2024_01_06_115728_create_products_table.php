<?php

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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id');
            $table->integer('company_id')->comment('メーカーid');
            $table->string('product_name', 20)->comment('名前');
            $table->integer('price')->comment('値段');
            $table->integer('stock')->comment('在庫');
            $table->text('img_path')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
