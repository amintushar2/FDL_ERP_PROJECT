<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inv_audit_trail', function (Blueprint $table) {
            $table->id('audit_id');
            $table->string('user_id', 100)->nullable();
            $table->string('action', 50);
            $table->string('table_name', 100);
            $table->unsignedBigInteger('record_id')->nullable();
            $table->longText('old_value')->nullable();
            $table->longText('new_value')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('inv_audit_trail'); }
};