<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── 1. Admin Account ────────────────────────────────────────────────
        $admin = User::updateOrCreate(
            ['email' => 'admin@unilak.ac.rw'],
            [
                'name'          => 'UNILAK Administrator',
                'email'         => 'admin@unilak.ac.rw',
                'password'      => Hash::make('Admin@1234'),
                'student_id'    => 'ADM-0001',
                'department'    => 'Academic Affairs',
                'year_of_study' => null,
                'role'          => 'admin',
                'bio'           => 'Official UNILAK Forum administrator and moderation account.',
            ]
        );

        // ── 2. Sample Student Account ───────────────────────────────────────
        $student = User::updateOrCreate(
            ['email' => 'student@unilak.ac.rw'],
            [
                'name'          => 'Christian Mugisha',
                'email'         => 'student@unilak.ac.rw',
                'password'      => Hash::make('Student@1234'),
                'student_id'    => '2023/CS/045',
                'department'    => 'Computing & Information Sciences',
                'year_of_study' => 3,
                'role'          => 'student',
                'bio'           => 'Third-year CS student interested in full-stack web development and cloud technologies.',
            ]
        );

        // ── 3. Seed Categories ─────────────────────────────────────────────
        $this->call(CategorySeeder::class);

        // ── 4. Seed Standard Tags ──────────────────────────────────────────
        $tags = [
            'Laravel', 'PHP', 'MySQL', 'TailwindCSS', 'JavaScript',
            'Exam Prep', 'Past Papers', 'Assignment Help', 'Internship',
            'Kigali Campus', 'Nyanza Campus', 'Rwamagana Campus',
            'Announcement', 'Graduation', 'Academic Calendar'
        ];

        foreach ($tags as $tagName) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName, 'slug' => Str::slug($tagName)]
            );
        }

        // ── 5. Seed Welcome Announcement ───────────────────────────────────
        Announcement::updateOrCreate(
            ['title' => 'Welcome to the Official UNILAK Student Academic Forum!'],
            [
                'admin_id'     => $admin->id,
                'title'        => 'Welcome to the Official UNILAK Student Academic Forum!',
                'content'      => "Dear UNILAK students and faculty,\n\nWe are pleased to introduce the centralized UNILAK Student Academic Forum. This platform replaces fragmented WhatsApp groups with an organized, searchable repository of academic questions, course discussions, study materials, and official university communications.\n\nPlease adhere to academic integrity guidelines when sharing materials or seeking coursework assistance.",
                'department'   => null, // All departments
                'is_pinned'    => true,
                'published_at' => now(),
            ]
        );

        $this->command->info('✅ Admin seeded: admin@unilak.ac.rw / Admin@1234');
        $this->command->info('✅ Student seeded: student@unilak.ac.rw / Student@1234');
        $this->command->info('✅ Categories, tags, and initial announcement seeded successfully.');
    }
}
