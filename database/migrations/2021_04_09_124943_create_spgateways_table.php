<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpgatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spgateways', function (Blueprint $table) {
            $table->id();
            $table->string('order_number',16)->nullable()->defaule('')->comment('訂單編號');
            $table->unsignedInteger('amount')->nullable()->default(0)->comment('訂單id');
            $table->boolean('pay_status')->comment('付款狀態，-1:訂單失敗 0:訂單建立 1:付款完成 2：等待付款');
            $table->string('PaymentType',16)->nullable()->defaule('')->comment('付款類別');
            $table->string('memo',60)->nullable()->defaule('')->comment('備註');
            $table->longText('post_json')->nullable()->defaule('')->comment('post資料');
            $table->longText('get_json')->nullable()->defaule('')->comment('get資料');
            $table->longText('result_json')->nullable()->defaule('')->comment('result資料');
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
        Schema::dropIfExists('spgateways');
    }
}
