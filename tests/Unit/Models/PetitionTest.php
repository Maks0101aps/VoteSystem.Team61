<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Petition;
use App\Models\PetitionSignature;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PetitionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Проверяем, существует ли таблица petitions в тестовой БД
        if (!Schema::hasTable('petitions')) {
            $this->markTestSkipped('Таблица petitions не существует.');
        }
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $petition = new Petition();
        $fillable = [
            'title',
            'description',
            'signatures_required',
            'user_id',
            'duration',
            'school_class_id',
            'status',
        ];

        $this->assertEquals($fillable, $petition->getFillable());
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $petition->user);
        $this->assertEquals($user->id, $petition->user->id);
    }

    /** @test */
    public function it_belongs_to_school_class()
    {
        $schoolClass = SchoolClass::factory()->create();
        $petition = Petition::factory()->create(['school_class_id' => $schoolClass->id]);

        $this->assertInstanceOf(SchoolClass::class, $petition->schoolClass);
        $this->assertEquals($schoolClass->id, $petition->schoolClass->id);
    }

    /** @test */
    public function it_has_many_signatures()
    {
        $petition = Petition::factory()->create();
        $user = User::factory()->create();
        $signature = PetitionSignature::factory()->create([
            'petition_id' => $petition->id,
            'user_id' => $user->id
        ]);

        $this->assertInstanceOf(PetitionSignature::class, $petition->signatures->first());
        $this->assertEquals(1, $petition->signatures->count());
    }

    /** @test */
    public function it_has_many_comments()
    {
        $petition = Petition::factory()->create();
        $comment = Comment::factory()->create([
            'commentable_id' => $petition->id,
            'commentable_type' => Petition::class
        ]);

        $this->assertInstanceOf(Comment::class, $petition->comments->first());
        $this->assertEquals(1, $petition->comments->count());
    }

    /** @test */
    public function it_can_determine_if_user_signed_petition()
    {
        $petition = Petition::factory()->create();
        $user = User::factory()->create();
        
        // Предполагаем, что метод isSignedByUser существует
        $this->assertFalse($petition->isSignedByUser($user));
        
        PetitionSignature::factory()->create([
            'petition_id' => $petition->id,
            'user_id' => $user->id
        ]);
        
        $this->assertTrue($petition->fresh()->isSignedByUser($user));
    }

    /** @test */
    public function it_can_count_signatures()
    {
        $petition = Petition::factory()->create();
        
        // Предполагаем наличие accessor для signatures_count
        $this->assertEquals(0, $petition->signatures()->count());
        
        PetitionSignature::factory()->count(5)->create([
            'petition_id' => $petition->id
        ]);
        
        $this->assertEquals(5, $petition->fresh()->signatures()->count());
    }

    /** @test */
    public function it_can_filter_by_status()
    {
        Petition::factory()->create(['status' => 'active']);
        Petition::factory()->create(['status' => 'approved']);
        Petition::factory()->create(['status' => 'rejected']);
        
        $this->assertEquals(1, Petition::where('status', 'active')->count());
        $this->assertEquals(1, Petition::where('status', 'approved')->count());
        $this->assertEquals(1, Petition::where('status', 'rejected')->count());
    }
} 