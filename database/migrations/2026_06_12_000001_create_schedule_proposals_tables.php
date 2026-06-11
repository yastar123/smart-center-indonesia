<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedule_proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->enum('proposed_by_type', ['guru', 'siswa']);
            $table->unsignedBigInteger('proposed_by_id');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('jenis', ['online', 'offline', 'private'])->default('offline');
            $table->string('ruangan')->nullable();
            $table->string('link_meeting')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('schedule_id')->nullable()->comment('Filled after approved');
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('schedules')->nullOnDelete();
        });

        Schema::create('schedule_proposal_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->enum('approver_type', ['guru', 'siswa']);
            $table->unsignedBigInteger('approver_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('schedule_proposals')->onDelete('cascade');
            $table->unique(['proposal_id', 'approver_type', 'approver_id'], 'proposal_approvals_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule_proposal_approvals');
        Schema::dropIfExists('schedule_proposals');
    }
};
