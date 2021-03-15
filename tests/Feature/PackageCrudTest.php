<?php

namespace Tests\Feature;

use App\Collaborator;
use App\Events\PackageCreated;
use App\Favorite;
use App\Listeners\SendNewPackageNotification;
use App\Notifications\NewPackage;
use App\Package;
use App\Tag;
use App\Tighten;
use App\User;
use Facades\App\Repo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PackageCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function app_package_index_shows_my_packages()
    {
        $user = User::factory()->create();
        $collaborator = Collaborator::factory()->create();
        $user->collaborators()->save($collaborator);

        $package = $collaborator->authoredPackages()->save(Package::factory()->make());

        $response = $this->be($user)->get(route('app.packages.index'));

        $response->assertSee($package->name);
        $response->assertSee(route('packages.show', [
            'namespace' => $package->composer_vendor,
            'name' => $package->composer_package,
        ]));
        $response->assertSee(route('app.packages.edit', $package));
    }

    /** @test */
    public function app_package_index_doesnt_show_others_packages()
    {
        $this->markTestIncomplete('Needs to scope just to the "My Packages" section');

        $user1 = User::factory()->create();
        $collaborator1 = Collaborator::factory()->create();
        $user1->collaborators()->save($collaborator1);

        $collaborator2 = Collaborator::factory()->create();

        $package = $collaborator2->authoredPackages()->save(Package::factory()->make());

        $response = $this->be($user1)->get(route('app.packages.index'));

        $response->assertDontSee($package->name);
    }

    /** @test */
    public function authenticated_user_can_see_create_package_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('app.packages.create'))
            ->assertOk()
            ->assertSee('Submit Package');
    }
}
