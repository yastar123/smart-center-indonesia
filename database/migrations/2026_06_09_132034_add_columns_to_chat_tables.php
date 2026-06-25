<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('chat_rooms', 'nama_room')) {
            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->string('nama_room', 100)->after('id');
            });
        }
        if (!Schema::hasColumn('chat_rooms', 'jenis_room')) {
            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->string('jenis_room', 20)->default('grup')->after('nama_room');
            });
        }
        if (!Schema::hasColumn('chat_rooms', 'cabang_id')) {
            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->unsignedBigInteger('cabang_id')->nullable()->after('jenis_room');
            });
        }
        if (!Schema::hasColumn('chat_rooms', 'peserta_id')) {
            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->json('peserta_id')->nullable()->after('cabang_id');
            });
        }
        if (!Schema::hasColumn('chat_rooms', 'waktu_pesan_terakhir')) {
            Schema::table('chat_rooms', function (Blueprint $table) {
                $table->timestamp('waktu_pesan_terakhir')->nullable()->after('peserta_id');
            });
        }

        if (!Schema::hasColumn('chat_messages', 'room_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->unsignedBigInteger('room_id')->after('id');
            });
        }
        if (!Schema::hasColumn('chat_messages', 'pengirim_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->unsignedBigInteger('pengirim_id')->after('room_id');
            });
        }
        if (!Schema::hasColumn('chat_messages', 'jenis')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->string('jenis', 20)->default('teks')->after('pengirim_id');
            });
        }
        if (!Schema::hasColumn('chat_messages', 'pesan')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->text('pesan')->nullable()->after('jenis');
            });
        }
        if (!Schema::hasColumn('chat_messages', 'file_path')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->string('file_path')->nullable()->after('pesan');
            });
        }
        if (!Schema::hasColumn('chat_messages', 'dibaca_oleh')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->json('dibaca_oleh')->nullable()->after('file_path');
            });
        }
        if (!Schema::hasColumn('chat_messages', 'is_deleted')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->boolean('is_deleted')->default(false)->after('dibaca_oleh');
            });
        }

        if (!Schema::hasColumn('chat_messages', 'room_id') || !Schema::hasColumn('chat_messages', 'pengirim_id')) {
            // no-op; columns are ensured above
        }

        try {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->foreign('room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
                $table->foreign('pengirim_id')->references('id')->on('users')->onDelete('cascade');
            });
        } catch (\Throwable $e) {
            // ignore duplicate foreign key errors on reruns
        }
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
