<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inv_assets', function (Blueprint $table) {
            $table->id('asset_id');
            $table->unsignedBigInteger('item_id');
            $table->string('serial_number', 200)->unique()->nullable();
            $table->text('qr_code')->nullable();
            $table->string('condition', 50)->default('GOOD');
            $table->string('status', 50)->default('AVAILABLE');
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 15, 2)->default(0);
            $table->date('warranty_expiry')->nullable();
            $table->string('location', 200)->nullable();
            $table->string('notes', 500)->nullable();
            $table->string('created_by', 100)->nullable();
            $table->timestamps();

            $table->foreign('item_id')
                  ->references('item_id')
                  ->on('inv_items');
        });
    }
    public function down(): void { Schema::dropIfExists('inv_assets'); }
};