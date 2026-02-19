<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class PostController extends Controller
{
    private const JSON_PLACEHOLDER_API_URL = 'https://jsonplaceholder.typicode.com/posts';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $posts = Post::paginate(10);
        return view('posts.index', compact('posts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Post $post): View
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Import posts from the JSONPlaceholder API.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function fetchAndImportPosts(): View | string
    {
        $response = Http::get(self::JSON_PLACEHOLDER_API_URL);

        if ($response->successful()) {
            $posts = $response->json();

            // For bulk update or create
            // foreach ($posts as $item) {
            //     Post::updateOrCreate(
            //         ['placeholder_id' => $item['id']],
            //         [
            //             'title' => $item['title'],
            //             'body'  => $item['body']
            //         ]
            //     );
            // }

            // For bulk insert
            collect($posts)->chunk(500)->each(function ($chunk) {
                Post::upsert(
                    $chunk->map(function ($item) {
                        return [
                            'placeholder_id' => $item['id'],
                            'title' => $item['title'],
                            'body' => $item['body'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    })->toArray(),
                    ['placeholder_id'],
                    ['title', 'body', 'updated_at']
                );
            });

            return redirect()->route('posts.index');
        }

        return "Failed to fetch data";
    }
}
