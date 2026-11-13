<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentFacultyAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_sees_only_their_own_faculty_department(): void
    {
        $this->seed();

        $student = User::where('email', 'student@unilak.ac.rw')->firstOrFail();

        $response = $this->actingAs($student)->get('/categories');

        $response->assertOk();
        $response->assertSee('Faculty of Computing & Information Sciences');
        $response->assertDontSee('Faculty of Economic Sciences & Management');
        $response->assertDontSee('Faculty of Law');
    }

    public function test_student_cannot_open_another_faculty_department_board(): void
    {
        $this->seed();

        $student = User::where('email', 'student@unilak.ac.rw')->firstOrFail();
        $otherFaculty = Category::where('name', 'Faculty of Law')->firstOrFail();

        $response = $this->actingAs($student)->get('/categories/'.$otherFaculty->slug);

        $response->assertStatus(403);
    }

    public function test_student_can_see_discussions_from_same_faculty(): void
    {
        $this->seed();

        $student = User::where('email', 'student@unilak.ac.rw')->firstOrFail();
        $facultyCategory = Category::where('name', 'Faculty of Computing & Information Sciences')->firstOrFail();

        $response = $this->actingAs($student)->get('/discussions?category='.$facultyCategory->slug);

        $response->assertOk();
        $response->assertSee('Faculty of Computing & Information Sciences');
    }

    public function test_student_discussion_feeds_only_show_posts_from_their_faculty(): void
    {
        $this->seed();

        $lawStudent = User::factory()->create([
            'role' => 'student',
            'department' => 'Faculty of Law',
        ]);
        $computingStudent = User::factory()->create([
            'role' => 'student',
            'department' => 'Computing & Information Sciences',
        ]);
        $category = Category::where('slug', 'campus-life-student-union')->firstOrFail();

        $this->createPost($lawStudent, $category, 'Faculty visibility Law discussion');
        $this->createPost($computingStudent, $category, 'Faculty visibility Computing discussion');

        foreach ([
            '/dashboard',
            '/discussions',
            '/categories/'.$category->slug,
            '/search?q=Faculty%20visibility',
        ] as $url) {
            $response = $this->actingAs($lawStudent)->get($url);

            $response->assertOk();
            $response->assertSee('Faculty visibility Law discussion');
            $response->assertDontSee('Faculty visibility Computing discussion');
        }

        $categoryIndex = $this->actingAs($lawStudent)->get('/categories');
        $visibleInterestCategories = $categoryIndex->viewData('interestCategories');

        $this->assertSame(1, $visibleInterestCategories->firstWhere('id', $category->id)->posts_count);
    }

    public function test_students_from_other_faculties_cannot_view_or_reply_to_a_discussion(): void
    {
        $this->seed();

        $lawStudent = User::factory()->create([
            'role' => 'student',
            'department' => 'Faculty of Law',
        ]);
        $computingStudent = User::factory()->create([
            'role' => 'student',
            'department' => 'Computing & Information Sciences',
        ]);
        $category = Category::where('slug', 'campus-life-student-union')->firstOrFail();
        $post = $this->createPost($lawStudent, $category, 'Law-only discussion');

        $this->actingAs($computingStudent)
            ->get('/discussions/'.$post->id)
            ->assertForbidden();

        $this->actingAs($computingStudent)
            ->post('/discussions/'.$post->id.'/replies', ['content' => 'This should be blocked.'])
            ->assertForbidden();

        $this->assertDatabaseMissing('replies', [
            'post_id' => $post->id,
            'user_id' => $computingStudent->id,
        ]);
    }

    public function test_students_from_the_same_faculty_can_reply_to_a_discussion(): void
    {
        $this->seed();

        $lawAuthor = User::factory()->create([
            'role' => 'student',
            'department' => 'Faculty of Law',
        ]);
        $lawStudent = User::factory()->create([
            'role' => 'student',
            'department' => 'Law',
        ]);
        $category = Category::where('slug', 'campus-life-student-union')->firstOrFail();
        $post = $this->createPost($lawAuthor, $category, 'Law student discussion');

        $this->actingAs($lawStudent)
            ->post('/discussions/'.$post->id.'/replies', ['content' => 'A reply from another Law student.'])
            ->assertRedirect(route('posts.show', $post));

        $this->assertDatabaseHas('replies', [
            'post_id' => $post->id,
            'user_id' => $lawStudent->id,
            'content' => 'A reply from another Law student.',
        ]);
    }

    private function createPost(User $author, Category $category, string $title): Post
    {
        return Post::create([
            'user_id' => $author->id,
            'category_id' => $category->id,
            'title' => $title,
            'content' => 'This discussion is limited to the faculty of its student author.',
            'last_activity_at' => now(),
        ]);
    }
}
