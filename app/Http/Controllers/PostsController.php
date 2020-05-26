<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    public function index($page = 1) {
        $limit = 12;
        $posts = Post::orderBy('created_at', 'DESC')
            ->offset(($page - 1)  * $limit)
            ->limit($limit)
            ->get()
        ;

        $count_all_posts = Post::count();

        return response()->json([
            'count_pages' => ceil($count_all_posts/$limit),
            'posts' => $posts
        ]);
    }

    public function getPost($id) {
        return response()->json(Post::whereId($id)->firstOrFail());
    }

    public function create(Request $request) {

        $request->validate([
            'header' => ['required', 'string', 'min:3', 'max:250'],
            'category_id' => ['required', 'integer'],
            'preview_text' => ['required', 'string', 'min:50', 'max:300'],
            'text' => ['required', 'string', 'min:50', 'max:10000']
        ]);

        $save_data = $request->only(['header', 'category_id', 'preview_text', 'text']);

        $save_data['author_id'] = 1;

        $result = Post::create($save_data);

        return response()->json([
            'status' => $result ? 'success' : 'error',
            'data' => $result ?: [],
            'message' => $result ? 'Saved' : 'Error',
        ]);
    }

    public function edit($id, Request $request) {
        $request->validate([
            'header' => ['required', 'string', 'min:3', 'max:250'],
            'category_id' => ['required', 'integer'],
            'preview_text' => ['required', 'string', 'min:50', 'max:300'],
            'text' => ['required', 'string', 'min:50', 'max:10000']
        ]);

        $update_data = $request->only(['header', 'category_id', 'preview_text', 'text']);

        $post = Post::whereId($id)->firstOrFail();
        $fillable = $post->getFillable();

        foreach ($fillable as $field) {
            if(isset($update_data[$field])) {
                $post->$field = $update_data[$field];
            }
        }

        $result = $post->save();

        return response()->json([
            'status' => $result ? 'success' : 'error',
            'data' => $result ? $post : [],
            'message' => $result ? 'Saved' : 'Error'
        ]);
    }

    public function delete($id) {
        $post = Post::whereId($id)->firstOrFail();

        $result = $post->delete();

        return response()->json([
            'status' => $result ? 'success' : 'error',
            'data' => [],
            'message' => $result ? 'Deleted' : 'Error'
        ]);
    }
}
