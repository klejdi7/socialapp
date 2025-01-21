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
        $posts = Post::doesntHave('comments')
            ->where('created_at', '<=', now()->subYear())
            ->get();

        foreach ($posts as $post) {
            $post->delete();
            $this->info("Soft deleted post: {$post->id}");
        }

        $this->info('Old posts removed successfully.');
    }
}
