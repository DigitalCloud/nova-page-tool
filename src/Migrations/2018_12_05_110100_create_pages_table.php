<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->json('title');
            $table->string('slug')->unique();
            $table->text('content')->nullable();

            $table->enum('status', ['draft', 'pending', 'published'])->default('published')->nullable();
            $table->enum('visibility', ['public', 'private', 'protected'])->default('public')->nullable();
            $table->string('password')->nullable();

            // page attribute
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('template')->nullable();
            $table->integer('order')->nullable();
            $table->string('featured_image')->nullable();

            // timestamps
            $table->timestamp('scheduled_for')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
