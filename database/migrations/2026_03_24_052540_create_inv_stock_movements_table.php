<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inv_stock_movements', function (Blueprint $table) {
            $table->id('movement_id');
            $table->unsignedBigInteger('item_id');
            $table->string('user_id', 100)->nullable();
            $table->string('type', 20);
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->string('reference_no', 100)->nullable();
            $table->string('notes', 500)->nullable();
            $table->date('movement_date');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('item_id')
                  ->references('item_id')
                  ->on('inv_items');
        });
    }
    public function down(): void { Schema::dropIfExists('inv_stock_movements'); }
};