<?php

use App\Models\Act;
use App\Models\Day;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('day')->unsigned();
            $table->string('name');
            $table->date('date');
            $table->boolean('current')->default(false);
        });

        // Loop through the acts table and add the day to the days table
        $acts = Act::all();

        foreach ($acts as $act) {
            $day = Day::query()->where('day', $act->day)->first();

            if (!$day) {
                $day = new Day();
                $day->day = $act->day;
                $day->name = 'Day ' . $act->day;
                $day->date = '2021-01-01';

                $day->save();
            }
        }

        // add foreign key to acts table to the exising day row
        Schema::table('acts', function (Blueprint $table) {
            $table->foreign('day')->references('day')->on('days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acts', function (Blueprint $table) {
            $table->dropForeign(['day']);
        });

        Schema::dropIfExists('days');
    }
};
