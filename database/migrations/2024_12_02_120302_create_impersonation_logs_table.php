<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImpersonationLogsTable extends Migration
{
    public function up()
    {
        Schema::create('impersonation_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('impersonator_id'); // Super admin who initiated impersonation
            $table->unsignedBigInteger('impersonated_user_id'); // User being impersonated
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->foreign('impersonator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('impersonated_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('impersonation_logs');
    }
}
