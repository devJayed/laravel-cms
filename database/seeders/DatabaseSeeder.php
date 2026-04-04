<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * DatabaseSeeder - Creates demo data
 *
 * Running this seeder will generate the necessary demo users
 * and posts to test the blog application.
 *
 * Demo Users:
 * - author@example.com (password: password) - Author role
 * - editor@example.com (password: password) - Editor role
 *
 * Command: php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===================================
        // Create Demo Users
        // ===================================

        // Author User - For writing blog posts
        $author = User::factory()->author()->create([
            'name' => 'Abdullah (Author)',
            'email' => 'author@example.com',
        ]);

        // Editor User - For approving/rejecting posts
        $editor = User::factory()->editor()->create([
            'name' => 'Rafiqul (Editor)',
            'email' => 'editor@example.com',
        ]);

        // Admin User - For managing everything
        $admin = User::factory()->admin()->create([
            'name' => 'Karim (Admin)',
            'email' => 'admin@example.com',
        ]);

        // Extra Author
        $author2 = User::factory()->author()->create([
            'name' => 'Fatema (Author)',
            'email' => 'fatema@example.com',
        ]);

        // ===================================
        // Create Demo Posts
        // ===================================

        // Author's Draft post
        Post::factory()
            ->draft()
            ->forUser($author)
            ->create([
                'title' => 'Building a Blog with Laravel - Draft',
                'slug' => 'building-blog-laravel-draft',
                'body' => 'This is a draft post that has not been submitted yet. The author can edit it and submit when ready.

Laravel is a PHP web application framework known for its expressive and elegant syntax. It follows the MVC (Model-View-Controller) architectural pattern.',
            ]);

        // Author's Pending post
        Post::factory()
            ->pending()
            ->forUser($author)
            ->create([
                'title' => 'Understanding Laravel Routing',
                'slug' => 'understanding-laravel-routing',
                'body' => 'This post is waiting for approval.

In Laravel, routes are defined in the routes folder using web.php and api.php files. Web routes include session state and CSRF protection, while API routes are stateless.

You can use HTTP methods like Route::get(), Route::post(), Route::put(), Route::delete().',
            ]);

        // Author's Published post
        Post::factory()
            ->published()
            ->forUser($author)
            ->create([
                'title' => 'Introduction to Laravel Eloquent ORM',
                'slug' => 'laravel-eloquent-orm-introduction',
                'body' => 'Eloquent is Laravel’s built-in ORM (Object-Relational Mapping) that makes working with databases easy.

Each database table has a corresponding Model class. Using models, we can retrieve, insert, update, and delete data.

Example:
$user = User::find(1);
$posts = Post::where("status", "published")->get();
$newPost = Post::create(["title" => "New Post", "body" => "..."]);

Eloquent relationships (hasOne, hasMany, belongsTo, belongsToMany) are used to define relationships between tables.',
                'published_at' => now()->subDays(5),
            ]);

        // Author's Rejected post
        Post::factory()
            ->rejected()
            ->forUser($author)
            ->create([
                'title' => 'This Post Has Been Rejected',
                'slug' => 'post-has-been-rejected',
                'body' => 'This is a rejected post. The editor rejected it for some reason. The author can edit and resubmit it.',
                'rejection_reason' => 'Please add more detailed information and include code examples.',
            ]);

        // Author2's posts
        Post::factory()
            ->published()
            ->forUser($author2)
            ->create([
                'title' => 'Laravel Blade Template Engine',
                'slug' => 'laravel-blade-template-engine',
                'body' => 'Blade is Laravel’s powerful template engine. It combines plain PHP code with clean templating.

Blade Features:
- Template inheritance (@extends, @section, @yield)
- Components (@component, <x-component>)
- Control structures (@if, @foreach, @for)
- Raw PHP (@php)

Blade files use the .blade.php extension and are stored in the resources/views folder.',
                'published_at' => now()->subDays(3),
            ]);

        Post::factory()
            ->pending()
            ->forUser($author2)
            ->create([
                'title' => 'What is Laravel Middleware and How It Works',
                'slug' => 'laravel-middleware-how-it-works',
                'body' => 'Middleware is an HTTP request filtering mechanism. A request passes through middleware before reaching the controller.

Common uses:
- Authentication check
- CSRF verification
- Logging
- Rate limiting

php artisan make:middleware CheckAge
This command creates a new middleware.',
            ]);

        // Extra published posts for better demo
        Post::factory()
            ->published()
            ->forUser($author)
            ->create([
                'title' => 'Laravel Migration and Database Schema',
                'slug' => 'laravel-migration-database-schema',
                'body' => 'Migration is a database version control system. It is used to define and modify database schema.

php artisan make:migration create_posts_table
php artisan migrate

Migration files have up() and down() methods. up() applies changes, down() rolls them back.

Schema Builder allows defining column types:
$table->string("name");
$table->text("body");
$table->integer("count");
$table->boolean("is_active");
$table->timestamps();',
                'published_at' => now()->subDays(7),
            ]);

        // Editor also has a post (Editor can also write posts)
        Post::factory()
            ->published()
            ->forUser($editor)
            ->create([
                'title' => 'Laravel Authorization - Policies and Gates',
                'slug' => 'laravel-authorization-policies-gates',
                'body' => 'Authorization determines what actions a user can perform.

Gates:
Simple closure-based authorization checks.

Policies:
Used to organize model-specific authorization logic.

php artisan make:policy PostPolicy --model=Post

Policy methods:
- viewAny, view, create, update, delete

In Controller:
$this->authorize("update", $post);

In Blade:
@can("update", $post)
    // Edit button
@endcan',
                'published_at' => now()->subDays(2),
            ]);

        // Output message
        $this->command->info('Demo data created successfully!');
        $this->command->info('');
        $this->command->info('Demo Users:');
        $this->command->info('  Admin:  admin@example.com (password: password)');
        $this->command->info('  Editor: editor@example.com (password: password)');
        $this->command->info('  Author: author@example.com (password: password)');
        $this->command->info('  Author: fatema@example.com (password: password)');
    }
}
