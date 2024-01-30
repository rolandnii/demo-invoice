<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('invoices');

        Schema::create('invoices', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUlid('customer_id')->constrained(
                table: 'users' , indexName: 'customer_id'
            )->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('total_amount');
            $table->date('issue_date');
            $table->date('due_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
