<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Facade;

class ProjectSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $type_id = Type::select('id')->pluck('id')->all();
        $type_id[] = null;

        for ($i = 0; $i < 20; $i++) {
            $project = new Project();
            $project->type_id = $faker->randomElement($type_id);
            $project->name = $faker->words(2, true);
            $project->description = $faker->paragraphs(4, true);
            $project->project_link = $faker->url();
            $project->published = $faker->boolean();
            $project->save();
        }
    }
}
