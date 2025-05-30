<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Http\Resources\PostResource;

use Illuminate\Http\Request;

class SavePostController extends Controller
{
    public function savePost($postId){
        $user = Auth::user();
        $post = Post::findOrFail($postId);

        
        if (!$user->favorites()->where('post_id', $postId)->exists()) {
 
            $user->favorites()->attach($postId);

     
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ البوست بنجاح.',
                'data' => $post
            ], 200);
        }

       
        return response()->json([
            'success' => false,
            'message' => 'البوست محفوظ مسبقًا.',
        ], 400);
    }


    public function showFavorites(){


        $user = Auth::user(); 

        $favorites = $user->favorites()
                        ->with(['user.profile', 'governorate', 'skills'])
                        ->paginate(10);

        if ($favorites->isEmpty()) {
            return response()->json([
                'message' => 'لا يوجد محفوظات',
                'data' => [],
            ]);
        }
        return PostResource::collection($favorites);
    }


    public function removeFavorite($postId){
        $user = Auth::user(); 
        $post = Post::findOrFail($postId); 

       
        if ($user->favorites()->where('post_id', $postId)->exists()) {
            $user->favorites()->detach($postId); 

           
            return response()->json([
                'success' => true,
                'message' => 'تم إزالة البوست من المحفوظات.',
            ], 200);
        }

       
        return response()->json([
            'success' => false,
            'message' => 'البوست غير موجود في المحفوظات.',
        ], 400);
    }

}
