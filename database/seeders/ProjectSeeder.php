<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for ($i = 0; $i < 5; $i++) {
            $project = new Project();
            $project->name = $faker->words(2, true);
            $project->description = $faker->paragraphs(4, true);
            $project->project_link = $faker->url();
            $project->published = $faker->boolean();
            $project->save();
        }
    }
}
