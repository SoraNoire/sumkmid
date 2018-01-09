<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProvinsiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    private static $provinsi = [
        ['id'=>1, 'nama_provinsi'=>'DKI Jakarta', 'inisial'=> 'jakarta'],
        ['id'=>2, 'nama_provinsi'=>'Jawa Barat', 'inisial'=> 'jabar'],
        ['id'=>3, 'nama_provinsi'=>'Banten', 'inisial'=> 'banten'],
        ['id'=>4, 'nama_provinsi'=>'Bali', 'inisial'=> 'bali'],
        ['id'=>5, 'nama_provinsi'=>'Bangka Belitung', 'inisial'=> 'bangka-belitung'],
        ['id'=>6, 'nama_provinsi'=>'Bengkulu', 'inisial'=> 'bengkulu'],
        ['id'=>7, 'nama_provinsi'=>'Gorontalo', 'inisial'=> 'gorontalo'],
        ['id'=>8, 'nama_provinsi'=>'Jambi', 'inisial'=> 'jambi'],
        ['id'=>9, 'nama_provinsi'=>'Jawa Tengah', 'inisial'=> 'jateng'],
        ['id'=>10, 'nama_provinsi'=>'Jawa Timur', 'inisial'=> 'jatim'],
        ['id'=>11, 'nama_provinsi'=>'Kalimantan Barat', 'inisial'=> 'kalbar'],
        ['id'=>12, 'nama_provinsi'=>'Kalimantan Selatan', 'inisial'=> 'kalsel'],
        ['id'=>13, 'nama_provinsi'=>'Kalimantan Tengah', 'inisial'=> 'kalteng'],
        ['id'=>14, 'nama_provinsi'=>'Kalimantan Timur', 'inisial'=> 'kaltim'],
        ['id'=>15, 'nama_provinsi'=>'Kepulauan Riau', 'inisial'=> 'kepulauan-riau'],
        ['id'=>16, 'nama_provinsi'=>'Lampung', 'inisial'=> 'lampung'],
        ['id'=>17, 'nama_provinsi'=>'Maluku', 'inisial'=> 'maluku'],
        ['id'=>18, 'nama_provinsi'=>'Maluku Utara', 'inisial'=> 'malut'],
        ['id'=>19, 'nama_provinsi'=>'Papua Barat', 'inisial'=> 'papua-barat'],
        ['id'=>20, 'nama_provinsi'=>'Nusa Tenggara Barat', 'inisial'=> 'ntb'],
        ['id'=>21, 'nama_provinsi'=>'Nusa Tenggara Timur', 'inisial'=> 'ntt'],
        ['id'=>22, 'nama_provinsi'=>'Papua', 'inisial'=> 'papua'],
        ['id'=>23, 'nama_provinsi'=>'Riau', 'inisial'=> 'riau'],
        ['id'=>24, 'nama_provinsi'=>'Sulawesi Selatan', 'inisial'=> 'sulsel'],
        ['id'=>25, 'nama_provinsi'=>'Sulawesi Tengah', 'inisial'=> 'sulteng'],
        ['id'=>26, 'nama_provinsi'=>'Sulawesi Tenggara', 'inisial'=> 'sultra'],
        ['id'=>27, 'nama_provinsi'=>'Sulawesi Utara', 'inisial'=> 'sulut'],
        ['id'=>28, 'nama_provinsi'=>'Sumatera Barat', 'inisial'=> 'sumbar'],
        ['id'=>29, 'nama_provinsi'=>'Sumatera Selatan', 'inisial'=> 'sumsel'],
        ['id'=>30, 'nama_provinsi'=>'Sumatera Utara', 'inisial'=> 'sumut'],
        ['id'=>31, 'nama_provinsi'=>'DI Yogyakarta', 'inisial'=> 'yogya'],
        ['id'=>32, 'nama_provinsi'=>'Sulawesi Barat', 'inisial'=> 'sulbar'],
        ['id'=>33, 'nama_provinsi'=>'Aceh D.I', 'inisial'=> 'aceh-di'],
        ['id'=>34, 'nama_provinsi'=>'Kalimantan Utara', 'inisial'=> 'kalut']
    ];

    public function up()
    {
        Schema::create('provinsi', function (Blueprint $table) {
          $table->bigincrements('id');
          $table->string('nama_provinsi');
          $table->string('inisial');
        });

        DB::table('provinsi')->insert(self::$provinsi);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provinsi');
    }
}
