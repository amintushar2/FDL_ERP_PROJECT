<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inv_asset_maintenance', function (Blueprint $table) {
            $table->id('maint_id');
            $table->unsignedBigInteger('asset_id');
            $table->string('service_type', 200);
            $table->date('service_date');
            $table->date('next_service_date')->nullable();
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('vendor', 200)->nullable();
            $table->string('status', 30)->default('SCHEDULED');
            $table->longText('notes')->nullable();
            $table->string('performed_by', 100)->nullable();
            $table->timestamps();

            $table->foreign('asset_id')
                  ->references('asset_id')
                  ->on('inv_assets');
        });
    }
    public function down(): void { Schema::dropIfExists('inv_asset_maintenance'); }
};