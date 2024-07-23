migration create_laporans_table :
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('test_number');
            $table->integer('virtual_ccu');
            $table->integer('test_time');
            $table->decimal('success_rate', 5, 2);
            $table->decimal('error_rate', 5, 2);
            $table->integer('max_tps');
            $table->integer('request_per_minute');
            $table->integer('total_request');
            $table->json('http_codes')->default(json_encode([]));
            $table->json('total_errors')->default(json_encode([]));
            $table->json('labels')->nullable();
            $table->json('values')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporans');
    }
};
