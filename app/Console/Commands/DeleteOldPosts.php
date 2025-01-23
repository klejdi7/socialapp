<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class DeleteOldPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove posts that have had not a lot of activity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedPosts = Post::doesntHave('comments')
            ->where('created_at', '<=', now()->subYear())
            ->delete();

        $this->info("Soft deleted {$deletedPosts} posts.");
    }
}
