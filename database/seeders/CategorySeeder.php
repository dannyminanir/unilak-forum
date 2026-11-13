<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // ── Faculties (Departments) ─────────────────────────────────────
            [
                'name'        => 'Faculty of Computing & Information Sciences',
                'slug'        => 'faculty-computing-information-sciences',
                'type'        => 'department',
                'description' => 'Discussions for CS, IT, and Software Engineering students. Topics include programming, databases, networking, and software projects.',
                'icon'        => '💻',
                'color'       => '#1E3A5F',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Faculty of Economic Sciences & Management',
                'slug'        => 'faculty-economic-sciences-management',
                'type'        => 'department',
                'description' => 'Accounting, Finance, Economics, Marketing, and Human Resource Management discussions for UNILAK business students.',
                'icon'        => '📊',
                'color'       => '#0D9488',
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Faculty of Law',
                'slug'        => 'faculty-of-law',
                'type'        => 'department',
                'description' => 'Legal studies, moot court, Rwandan jurisprudence, constitutional law, and international law topics.',
                'icon'        => '⚖️',
                'color'       => '#7C2D12',
                'sort_order'  => 3,
            ],
            [
                'name'        => 'Faculty of Environmental Studies',
                'slug'        => 'faculty-environmental-studies',
                'type'        => 'department',
                'description' => 'Rural Development, Environmental Management, and Emergency Management programs at UNILAK.',
                'icon'        => '🌿',
                'color'       => '#15803D',
                'sort_order'  => 4,
            ],
            [
                'name'        => 'Faculty of Education',
                'slug'        => 'faculty-of-education',
                'type'        => 'department',
                'description' => 'Education theory, pedagogy, curriculum design, teacher training, and educational psychology discussions.',
                'icon'        => '🏫',
                'color'       => '#7C3AED',
                'sort_order'  => 5,
            ],

            // ── Courses ───────────────────────────────────────────────────
            [
                'name'        => 'Web Development & Frameworks',
                'slug'        => 'web-development-frameworks',
                'type'        => 'course',
                'description' => 'Coursework, assignments, PHP/Laravel, JavaScript, Tailwind, and database integration.',
                'icon'        => '🌐',
                'color'       => '#2563EB',
                'sort_order'  => 10,
            ],
            [
                'name'        => 'Data Structures & Algorithms',
                'slug'        => 'data-structures-algorithms',
                'type'        => 'course',
                'description' => 'Algorithmic thinking, complexity analysis, trees, graphs, and exam prep.',
                'icon'        => '🧩',
                'color'       => '#6366F1',
                'sort_order'  => 11,
            ],
            [
                'name'        => 'Database Management Systems',
                'slug'        => 'database-management-systems',
                'type'        => 'course',
                'description' => 'Relational database design, SQL, normalization, transactions, and indexing.',
                'icon'        => '🗄️',
                'color'       => '#4F46E5',
                'sort_order'  => 12,
            ],
            [
                'name'        => 'Corporate Finance & Accounting',
                'slug'        => 'corporate-finance-accounting',
                'type'        => 'course',
                'description' => 'Financial reporting, IFRS, auditing standards, and managerial accounting tutorials.',
                'icon'        => '📈',
                'color'       => '#0891B2',
                'sort_order'  => 13,
            ],
            [
                'name'        => 'Rwandan Constitutional Law',
                'slug'        => 'rwandan-constitutional-law',
                'type'        => 'course',
                'description' => 'Constitutional principles, governance structures, human rights, and case briefs.',
                'icon'        => '📜',
                'color'       => '#9333EA',
                'sort_order'  => 14,
            ],
            [
                'name'        => 'Curriculum & Educational Psychology',
                'slug'        => 'curriculum-educational-psychology',
                'type'        => 'course',
                'description' => 'Child development theories, learning styles, instructional design, and classroom management strategies.',
                'icon'        => '📚',
                'color'       => '#BE185D',
                'sort_order'  => 15,
            ],
            [
                'name'        => 'Environmental Management & Policy',
                'slug'        => 'environmental-management-policy',
                'type'        => 'course',
                'description' => 'Climate change, sustainable development, EIA, disaster risk reduction, and Rwanda environmental policy.',
                'icon'        => '🌍',
                'color'       => '#16A34A',
                'sort_order'  => 16,
            ],

            // ── Interests & General Student Life ──────────────────────────
            [
                'name'        => 'Internships & Career Opportunities',
                'slug'        => 'internships-career-opportunities',
                'type'        => 'interest',
                'description' => 'Job openings, industrial attachments, resume reviews, and career guidance for UNILAK students.',
                'icon'        => '💼',
                'color'       => '#F97316',
                'sort_order'  => 20,
            ],
            [
                'name'        => 'Campus Life & Student Union',
                'slug'        => 'campus-life-student-union',
                'type'        => 'interest',
                'description' => 'Campus events, sports, Adventist fellowship, guilds, and social discussions across Kigali, Nyanza, and Rwamagana campuses.',
                'icon'        => '🏛️',
                'color'       => '#E11D48',
                'sort_order'  => 21,
            ],
            [
                'name'        => 'Final Year Projects & Research',
                'slug'        => 'final-year-projects-research',
                'type'        => 'interest',
                'description' => 'Thesis proposals, methodology, supervisor consultations, and research paper reviews.',
                'icon'        => '🎓',
                'color'       => '#B45309',
                'sort_order'  => 22,
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}

