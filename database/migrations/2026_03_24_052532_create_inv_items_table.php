<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inv_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name', 200);
            $table->string('sku', 100)->unique();
            $table->string('description', 500)->nullable();
            $table->string('unit', 50);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('qty_on_hand', 10, 2)->default(0);
            $table->decimal('reorder_level', 10, 2)->default(0);
            $table->string('status', 20)->default('ACTIVE');
            $table->string('created_by', 100)->nullable();
            $table->timestamps();

            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('inv_categories');
        });
    }
    public function down(): void { Schema::dropIfExists('inv_items'); }
};