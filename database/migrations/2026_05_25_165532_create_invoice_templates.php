<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_templates', function (Blueprint $table) {
            $table->id();

            // Tenant / Subscriber
            $table->unsignedBigInteger('subscriber_id');

            // Template Details
            $table->string('name')->nullable();
            $table->boolean('is_active')->default(true);

            // Branding
            $table->string('logo_path')->nullable();
            $table->string('primary_color', 10)->nullable();
            $table->string('secondary_color', 10)->nullable();
            $table->string('font_family', 50)->nullable();

            // Layout Configuration
            $table->json('layout')->nullable();
            $table->json('fields_config')->nullable();

            $table->timestamps();

            // Indexes
            // $table->index('subscriber_id');

            // Uncomment if subscribers table exists
            // $table->foreign('subscriber_id')
            //     ->references('id')
            //     ->on('subscribers')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_templates');
    }
};
