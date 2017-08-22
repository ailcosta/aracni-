<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')
                                      ->on('suppliers')
                                      ->onDelete('cascade')
                                      ->onUpdate('cascade');
            $table->string("url",255)->nullable;
            $table->string("sku",25)->nullable;
            $table->string("name",200)->nullable;
            $table->text("description")->nullable;
            $table->boolean("enabled")->default(1);
            $table->integer("qty")->default(0);
            $table->integer("multiplicity")->default(1);
            $table->double("unit_price")->nullable;
            $table->boolean("update_required")->default(0);
            $table->timestamp('verified_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
