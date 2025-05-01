<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function store(Post $post){
        $user = Auth::user();

        $post = Post::where('id', $post->id)->lockForUpdate()->firstOrFail();

        $exists = Application::where('user_id', $user->id)
                            ->where('post_id', $post->id)
                            ->exists();

        if ($exists) {
            return response()->json(['message' => 'تم التقديم مسبقاً'], 409);
        }

        Application::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        return response()->json(['message' => 'تم التقديم بنجاح']);
    }


    public function index(){
        $user = Auth::user();
        $posts = $user->appliedPosts()->paginate(10);
        return response()->json($posts);
    }
    

    public function destroy(Post $post){

        $user = Auth::user();

        $application = Application::where('user_id', $user->id)
                                ->where('post_id', $post->id)
                                ->first();

        if (!$application) {
            return response()->json(['message' => 'لا يوجد تقديم لحذفه'], 404);
        }

        $application->delete();

        return response()->json(['message' => 'تم إلغاء التقديم']);
    }


}
