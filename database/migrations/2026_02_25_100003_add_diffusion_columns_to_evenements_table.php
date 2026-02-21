<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->string('num_rue')->nullable()->after('lieu');
            $table->string('allee')->nullable()->after('num_rue');
            $table->string('ville')->nullable()->after('allee');
            $table->string('code_postal', 10)->nullable()->after('ville');
            $table->string('pays')->nullable()->after('code_postal');
            $table->string('image')->nullable()->after('pays');
            $table->unsignedBigInteger('diffusion_id')->default(1)->after('user_id');
            $table->foreign('diffusion_id')->references('id')->on('diffusion');
            $table->boolean('annonciateur')->default(false)->after('diffusion_id');
        });
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->dropColumn([
                'num_rue', 'allee', 'ville', 'code_postal', 'pays',
                'image', 'diffusion_id', 'annonciateur',
            ]);
        });
    }
};
