<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use App\Services\PostFilterService;
use App\Services\SkillService;
use App\Models\Post;
use App\Models\Skill;
use App\Models\Governorate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{


    protected $skillService;

    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
    }

    public function index(Request $request)
    {
        $posts = Post::paginate(20);
    
        return response()->json($posts);
    }


    public function store(StorePostRequest $request, PostService $postService){
        $user = Auth::user();
    
        $skillNames = $request->input('skills', []);
    
        $skillIds = [];
    
        foreach ($skillNames as $skillName) {
          
            $skillName = trim($skillName);
    
            if (!empty($skillName)) {
                $skill = \App\Models\Skill::firstOrCreate(
                    ['name' => $skillName], 
                    ['name' => $skillName]  
                );
    
                $skillIds[] = $skill->id;
            }
        }
    
        $data = $request->except('skills');
    
        $post = $postService->create($user, $data);
    
        if (!empty($skillIds)) {
            $post->skills()->sync($skillIds);
        }
    
        return response()->json([
            'message' => 'Post created successfully.',
            'data' => new PostResource($post->load('skills', 'governorate')),
        ], 201);
    }
    



    public function show($id){

        $post = Post::with(['governorate', 'skills', 'user.profile'])->findOrFail($id);
        return new PostResource($post);
    }


    
    public function update(UpdatePostRequest $request, $id, PostService $postService){
        $user = Auth::user();

        $post = Post::findOrFail($id);

        $this->authorize('update', $post);
        $data = $request->except('skills');
        $postService->update($user, $id, $data);

        if ($request->has('skills')) { 
            $skillNames = $request->input('skills', []);

            if (empty($skillNames)) {
                return response()->json([
                    'message' => 'You must provide at least one skill.',
                ], 422); 
            }

            $skillIds = [];

            foreach ($skillNames as $skillName) {
                $skillName = trim($skillName);

                if (!empty($skillName)) {
                    $skill = \App\Models\Skill::firstOrCreate(
                        ['name' => $skillName],
                        ['name' => $skillName]
                    );

                    $skillIds[] = $skill->id;
                }
            }

            $post->skills()->sync($skillIds);
        }

        return new PostResource($post->load('skills', 'governorate'));
    }

    

    
    public function destroy($id, PostService $postService){
        $user = Auth::user();
        $post = Post::findOrFail($id);

        $this->authorize('delete', $post); 

        $postService->delete($user, $id);

        return response()->json(['message' => 'Post deleted successfully.'], 200);
    }
    

    public function filterByGovernorate(Request $request, PostFilterService $filterService){

        try {
            $posts = $filterService->filter($request->only('governorate_id'));

            return PostResource::collection($posts);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }



    public function filterBySkill($skillName){

        try {
            $posts = $this->skillService->getPostsBySkill($skillName);
            return PostResource::collection($posts);
        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }


    public function getMyPosts(){
        
        $user = Auth::user();

        $posts = Post::where('user_id', $user->id)
                    ->with(['skills', 'governorate', 'user.profile'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);  

        return PostResource::collection($posts);
    }



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
        $favorites = $user->favorites()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $favorites
        ], 200);
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













