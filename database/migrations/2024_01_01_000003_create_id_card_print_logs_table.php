<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('id_card_print_logs', function (Blueprint $table) {
            $table->id();
            $table->string('emp_no', 20);
            $table->string('card_type', 30); // bangla_front, bangla_back, bangla_single, process, temp, label, back
            $table->string('report_file', 100);
            $table->string('section_no', 20)->nullable();
            $table->date('from_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('printed_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('id_card_print_logs');
    }
};
