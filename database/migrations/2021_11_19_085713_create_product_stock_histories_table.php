<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductStockHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stock_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('product_id');
            $table->double('sell_qty')->default(0)->comment('Stock Out');
            $table->double('req_send')->default(0)->comment('Stock In & Product Send To Others Branch');
            $table->double('stock_out')->default(0)->comment('Stock Out');

            $table->double('purchase_qty')->default(0)->comment('Stock In');
            $table->double('req_received')->default(0)->comment('Stock In & Product Received From Others Branch');
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
        Schema::dropIfExists('product_stock_histories');
    }
}
