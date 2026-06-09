<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->string('nama_room', 100)->after('id');
            $table->string('jenis_room', 20)->default('grup')->after('nama_room');
            $table->unsignedBigInteger('cabang_id')->nullable()->after('jenis_room');
            $table->json('peserta_id')->nullable()->after('cabang_id');
            $table->timestamp('waktu_pesan_terakhir')->nullable()->after('peserta_id');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id')->after('id');
            $table->unsignedBigInteger('pengirim_id')->after('room_id');
            $table->string('jenis', 20)->default('teks')->after('pengirim_id');
            $table->text('pesan')->nullable()->after('jenis');
            $table->string('file_path')->nullable()->after('pesan');
            $table->json('dibaca_oleh')->nullable()->after('file_path');
            $table->boolean('is_deleted')->default(false)->after('dibaca_oleh');

            $table->foreign('room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
            $table->foreign('pengirim_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropForeign(['pengirim_id']);
            $table->dropColumn(['room_id', 'pengirim_id', 'jenis', 'pesan', 'file_path', 'dibaca_oleh', 'is_deleted']);
        });

        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->dropColumn(['nama_room', 'jenis_room', 'cabang_id', 'peserta_id', 'waktu_pesan_terakhir']);
        });
    }
};
