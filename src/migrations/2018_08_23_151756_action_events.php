<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActionEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_events', function (Blueprint $table) {
			$table->increments('id');
			$table->timestamp('created_at');
			
			$table->integer('user_id')->nullable();
			$table->integer('subject_id')->nullable();
			$table->string('subject_type')->nullable();
			
			$table->string('action_name');
			$table->string('meta_key')->nullable();
			$table->text('meta_value')->nullable();
			$table->text('extra')->nullable();
			
			$table->index('subject_id');
			
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('action_events');
    }
}
