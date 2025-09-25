<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Module;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class ModuleAssignmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an admin who is also a lecturer can assign a module to another lecturer.
     *
     * @return void
     */
    public function test_admin_with_lecturer_role_can_assign_module_to_another_lecturer()
    {
        // 1. Setup: Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $lecturerRole = Role::create(['name' => 'lecturer']);

        // 2. Create an admin user and a lecturer user
        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);
        $adminUser->assignRole($lecturerRole); // The critical part: admin is also a lecturer

        $lecturerUser = User::factory()->create();
        $lecturerUser->assignRole($lecturerRole);

        // 3. Log in as the admin user
        $this->actingAs($adminUser);

        // 4. Prepare module data for creation
        $moduleData = [
            'title' => 'Test Module by Admin',
            'slug' => 'test-module-by-admin',
            'description' => 'A test description.',
            'pass_score' => 80,
            'is_active' => true,
            'user_id' => $lecturerUser->id, // Admin intends to assign it to the lecturer
        ];

        // 5. Send a POST request to create the module
        $response = $this->post(route('admin.modules.store'), $moduleData);

        // 6. Assertions
        $response->assertStatus(302); // Should redirect on success
        $response->assertRedirect(route('admin.modules.index'));

        // Check the database for the new module
        $this->assertDatabaseHas('modules', [
            'slug' => 'test-module-by-admin',
            'user_id' => $lecturerUser->id // This is the crucial check that should fail
        ]);

        // Also assert it was NOT assigned to the admin
        $this->assertDatabaseMissing('modules', [
            'slug' => 'test-module-by-admin',
            'user_id' => $adminUser->id
        ]);
    }
}