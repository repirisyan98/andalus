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
        Schema::create('tagihan_pelanggans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->date('tanggal');
            $table->unsignedFloat('meteran_awal');
            $table->unsignedFloat('meteran_akhir');
            $table->unsignedInteger('tarif');
            $table->unsignedInteger('biaya_admin');
            $table->date('tanggal_bayar')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('tagihan_pelanggans');
    }
};