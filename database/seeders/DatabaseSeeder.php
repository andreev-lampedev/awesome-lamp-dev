<?php

namespace Database\Seeders;

use App\Collaborator;
use App\Package;
use App\Tag;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory()->times(500)->create();

        foreach (Tag::PROJECT_TYPES as $name) {
            Tag::create(['name' => $name, 'slug' => Str::slug($name)]);
        }

        $packages = [
            Package::factory()->make([
                'name' => 'Nova Tabs Field',
                'abstract' => 'Simple Laravel Nova Tabs field.',
                'composer_name' => 'lampdev/tabs',
                'url' => 'https://github.com/lampdev/tabs',
                'readme_source' => 'github',
                'readme_format' => 'md',
                'readme' => "#Nova Tabs Field",
            ])
        ];

        Collaborator::factory()->times(2)->create()->each(function ($collaborator) use (&$packages) {
            $collaborator->authoredPackages()->save($packages[0]);
        });

        $users = User::all();

        // Give each of our main packages a jillion ratings
        Package::all()->each(function ($package) use ($users) {
            $users->shuffle();
            $users->take(50)->each(function ($user) use ($package) {
                $user->ratePackage($package->id, rand(1, 15) / 3);
            });
        });

        $tags = Tag::all();

        Package::factory()->times(400)->create();

        Package::all()->each(function ($package) use ($tags, $users) {
            $package->tags()->attach($tags->random()->take(3)->get());
            $users->random()->ratePackage($package->id, rand(1, 15) / 3);
            $users->random()->ratePackage($package->id, rand(1, 15) / 3);
            $users->random()->ratePackage($package->id, rand(1, 15) / 3);
        });

        // @todo make sure tags get synced up when pushing *anything* up to algolia
    }
}
