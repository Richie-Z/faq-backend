<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTable extends Migration
{

    public function __construct()
    {
        $this->table =
            [
                'users', 'admin', 'plans', 'user_plan',
                'user_detail', 'groups', 'faqs', 'answer_questions'
            ];
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->users();
        $this->admin();
        $this->plans();
        $this->userPlan();
        $this->userDetail();
        $this->group();
        $this->faq();
        $this->answerQuestion();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->table as $value) {
            Schema::dropIfExists($value);
        }
    }

    public function users(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_verified')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function admin(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }
    public function plans(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->timestamps();
        });
    }
    public function userPlan(): void
    {
        Schema::create('user_plan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->date('expires_at');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('set null');
        });
    }
    public function userDetail(): void
    {
        Schema::create('user_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function group(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
        });
    }
    public function faq(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->softDeletes();
        });
    }
    public function answerQuestion(): void
    {
        Schema::create('answer_question', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('answer');
            $table->boolean('is_verified')->default(1);
            $table->string('anonymous_add')->nullable();
            $table->unsignedBigInteger('faq_id');
            $table->timestamps();
            $table->foreign('faq_id')->references('id')->on('faqs')->onDelete('cascade');
        });
    }
}
