<?php

namespace Tests\Unit\Models;

use App\Models\SchoolClass;
use App\Models\User;
use App\Models\Petition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchoolClassTest extends TestCase
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
        $schoolClass = new SchoolClass;
        
        $this->assertEquals([
            'class_number',
            'class_letter',
        ], $schoolClass->getFillable());
    }

    /** @test */
    public function it_has_many_users()
    {
        $schoolClass = SchoolClass::factory()->create();
        $user1 = User::factory()->create(['school_class_id' => $schoolClass->id]);
        $user2 = User::factory()->create(['school_class_id' => $schoolClass->id]);
        
        $this->assertCount(2, $schoolClass->users);
        $this->assertEquals($user1->id, $schoolClass->users[0]->id);
        $this->assertEquals($user2->id, $schoolClass->users[1]->id);
    }

    /** @test */
    public function it_has_many_petitions()
    {
        if (!Schema::hasTable('petitions')) {
            $this->markTestSkipped('Таблица petitions не существует.');
        }
        
        $schoolClass = SchoolClass::factory()->create();
        $petition1 = Petition::factory()->create(['school_class_id' => $schoolClass->id]);
        $petition2 = Petition::factory()->create(['school_class_id' => $schoolClass->id]);
        
        $this->assertCount(2, $schoolClass->petitions);
        $this->assertEquals($petition1->id, $schoolClass->petitions[0]->id);
        $this->assertEquals($petition2->id, $schoolClass->petitions[1]->id);
    }

    /** @test */
    public function it_has_many_voting_visibilities()
    {
        $this->markTestSkipped('Not applicable. VotingVisibility uses class_number and class_letter directly, not school_class_id.');
    }

    /** @test */
    public function it_can_order_classes_by_number_and_letter()
    {
        // Create classes in random order
        SchoolClass::factory()->create(['class_number' => 11, 'class_letter' => 'A']);
        SchoolClass::factory()->create(['class_number' => 9, 'class_letter' => 'B']);
        SchoolClass::factory()->create(['class_number' => 10, 'class_letter' => 'C']);
        SchoolClass::factory()->create(['class_number' => 9, 'class_letter' => 'A']);
        SchoolClass::factory()->create(['class_number' => 10, 'class_letter' => 'A']);
        
        $classes = SchoolClass::ordered()->get();
        
        // The order should be: 9A, 9B, 10A, 10C, 11A
        $this->assertEquals(9, $classes[0]->class_number);
        $this->assertEquals('A', $classes[0]->class_letter);
        
        $this->assertEquals(9, $classes[1]->class_number);
        $this->assertEquals('B', $classes[1]->class_letter);
        
        $this->assertEquals(10, $classes[2]->class_number);
        $this->assertEquals('A', $classes[2]->class_letter);
        
        $this->assertEquals(10, $classes[3]->class_number);
        $this->assertEquals('C', $classes[3]->class_letter);
        
        $this->assertEquals(11, $classes[4]->class_number);
        $this->assertEquals('A', $classes[4]->class_letter);
    }

    /** @test */
    public function it_can_count_students_in_class()
    {
        $schoolClass = SchoolClass::factory()->create();
        
        // Create students in this class
        User::factory()->count(3)->create([
            'school_class_id' => $schoolClass->id,
            'role' => 'student'
        ]);
        
        // Create a teacher in this class (shouldn't be counted)
        User::factory()->create([
            'school_class_id' => $schoolClass->id,
            'role' => 'teacher'
        ]);
        
        $this->assertEquals(3, $schoolClass->countStudents());
    }

    /** @test */
    public function it_can_find_class_by_number_and_letter()
    {
        $class10A = SchoolClass::factory()->create(['class_number' => 10, 'class_letter' => 'A']);
        SchoolClass::factory()->create(['class_number' => 10, 'class_letter' => 'B']);
        
        $foundClass = SchoolClass::findByNumberAndLetter(10, 'A');
        
        $this->assertNotNull($foundClass);
        $this->assertEquals($class10A->id, $foundClass->id);
    }
} 