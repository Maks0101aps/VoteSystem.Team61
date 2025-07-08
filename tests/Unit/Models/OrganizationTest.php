<?php

namespace Tests\Unit\Models;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $organization = new Organization();
        $fillable = [
            'name',
            'email',
            'phone',
            'address',
            'city',
            'region',
            'country',
            'postal_code',
            'account_id',
            'user_id',
        ];

        $this->assertEquals($fillable, $organization->getFillable());
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $organization->user->id);
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $organization = Organization::factory()->create();
        
        $organization->delete();
        
        $this->assertSoftDeleted($organization);
    }

    /** @test */
    public function it_resolves_route_binding()
    {
        $organization = Organization::factory()->create();
        $organization->delete();
        
        $found = $organization->resolveRouteBinding($organization->id);
        
        $this->assertEquals($organization->id, $found->id);
        $this->assertTrue($found->trashed());
    }

    /** @test */
    public function it_can_format_address()
    {
        $organization = Organization::factory()->create([
            'address' => '123 Main St',
            'city' => 'New York',
            'region' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
        ]);
        
        $this->assertEquals('123 Main St, New York, NY, 10001, USA', $organization->formatAddress());
    }

    /** @test */
    public function it_scopes_to_ordered_query()
    {
        Organization::factory()->create(['name' => 'Beta Corp']);
        Organization::factory()->create(['name' => 'Alpha Inc']);
        Organization::factory()->create(['name' => 'Gamma LLC']);
        
        $ordered = Organization::ordered()->get();
        
        $this->assertEquals('Alpha Inc', $ordered[0]->name);
        $this->assertEquals('Beta Corp', $ordered[1]->name);
        $this->assertEquals('Gamma LLC', $ordered[2]->name);
    }
} 