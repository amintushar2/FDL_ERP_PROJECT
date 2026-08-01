<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inv_asset_assignments', function (Blueprint $table) {
            $table->id('assign_id');
            $table->unsignedBigInteger('asset_id');
            $table->string('user_id', 100)->nullable();
            $table->string('assigned_by', 100)->nullable();
            $table->date('assigned_date');
            $table->date('expected_return')->nullable();
            $table->date('return_date')->nullable();
            $table->string('return_condition', 50)->nullable();
            $table->decimal('deduction_amount', 15, 2)->default(0);
            $table->string('notes', 500)->nullable();
            $table->string('status', 20)->default('ACTIVE');
            $table->timestamps();

            $table->foreign('asset_id')
                  ->references('asset_id')
                  ->on('inv_assets');
        });
    }
    public function down(): void { Schema::dropIfExists('inv_asset_assignments'); }
};