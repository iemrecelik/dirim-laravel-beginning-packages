<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_permissions', function (Blueprint $table) {
            $table->increments('rol_perm_id');
            $table->unsignedInteger('rol_id');
            $table->unsignedInteger('perm_id');
            $table->foreign('rol_id')
                ->references('rol_id')->on('roles')
                ->onDelete('cascade');
            $table->foreign('perm_id')
                ->references('perm_id')->on('permissions')
                ->onDelete('cascade');
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
        Schema::dropIfExists('role_permission');
    }
}
