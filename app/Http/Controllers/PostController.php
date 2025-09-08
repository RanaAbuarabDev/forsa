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
use App\Models\Experience;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Notifications\PostMatchedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{

    use AuthorizesRequests;
    

    public function index(Request $request){
        
        $user = $request->user();

       
        $userSkills = $user->profile->skills->pluck('id')->toArray();
        $userExperiences = $user->profile->experiences->pluck('id')->toArray();

    
        $perPage = 20;
        $desiredMatchCount = (int) round($perPage * 0.35); 
     
        $matchedPosts = Post::with(['user.profile', 'governorate', 'skills'])
            ->where(function($query) use ($userSkills, $userExperiences) {
                $query->whereHas('skills', function($q) use ($userSkills) {
                    $q->whereIn('skills.id', $userSkills);
                })->orWhereHas('experience', function($q) use ($userExperiences) {
                    $q->whereIn('experiences.id', $userExperiences);
                });
            })
            ->latest()
            ->take($desiredMatchCount)
            ->get();

       
        $actualMatchCount = $matchedPosts->count();

        $otherCount = $perPage - $actualMatchCount;
        $otherPosts = Post::with(['user.profile', 'governorate', 'skills'])
            ->whereNotIn('id', $matchedPosts->pluck('id'))
            ->latest()
            ->take($otherCount)
            ->get();

       
        $allPosts = $matchedPosts->merge($otherPosts);
        $sortedPosts = $allPosts->sortByDesc('created_at')->values();

        
        $page = $request->get('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedPosts->forPage($page, $perPage),
            $sortedPosts->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return PostResource::collection($paginated);
    }


    public function store(StorePostRequest $request, PostService $postService){

        $user = Auth::user();

        // skills
        $skillNames = $request->input('skills', []);
        $skillIds = [];

        foreach ($skillNames as $skillName) {
            $skillName = trim($skillName);
            if (!empty($skillName)) {
                $skill = Skill::firstOrCreate(['name' => $skillName]);
                $skillIds[] = $skill->id;
            }
        }

        // experience
        $experienceName = trim($request->input('experience'));
        $experience = Experience::firstOrCreate(['job_title' => $experienceName]);

    
        $data = $request->except(['skills', 'experience']);
        $data['experience_id'] = $experience->id;

        $post = $postService->create($user, $data);

        if (!empty($skillIds)) {
            $post->skills()->sync($skillIds);
        }

        // إشعارات
        if ($post->type === 'job_creation') {
            $postGovernorateId = $post->governorate_id;

            $matchedUsers = User::whereHas('profile', function ($query) use ($postGovernorateId, $skillIds, $experienceName) {
                $query->where('governorate_id', $postGovernorateId)
                    ->where(function ($q) use ($skillIds, $experienceName) {
                        $q->whereHas('skills', function ($q2) use ($skillIds) {
                            $q2->whereIn('skills.id', $skillIds);
                        })
                        ->orWhereHas('experiences', function ($q3) use ($experienceName) {
                            $q3->where('job_title', $experienceName);
                        });
                    })
                    ->whereIn('employment_status', ['unemployed', 'seeking_better_opportunity']);
            })->get();


            Notification::send($matchedUsers, new PostMatchedNotification($post, $user));
        }

        return response()->json([
            'message' => 'Post created successfully.',
            'data' => new PostResource($post->load('skills', 'governorate')),
        ], 201);
    }

    
    public function show($id){

        $post = Post::with(['governorate', 'skills', 'user.profile','experience'])->findOrFail($id);
        return new PostResource($post);
    }


    public function update(UpdatePostRequest $request, $id, PostService $postService){

        $user = Auth::user();
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        // experience
        if ($request->has('experience')) {
            $experienceName = trim($request->input('experience'));
            $experience = \App\Models\Experience::firstOrCreate(['job_title' => $experienceName]);
        }

        // data
        $data = $request->except(['skills', 'experience']);
        if (isset($experience)) {
            $data['experience_id'] = $experience->id;
        }

        $post = $postService->update($user, $id, $data);

        // skills
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
                    $skill = \App\Models\Skill::firstOrCreate(['name' => $skillName]);
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
    

    public function getMyPosts(){
        
        $user = Auth::user();

        $posts = Post::where('user_id', $user->id)
                    ->with(['skills', 'governorate', 'user.profile'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);  

        return PostResource::collection($posts);
    }


    public function getUserPosts(User $user){
        
        $posts = $user->posts()
            ->with(['skills', 'governorate', 'user.profile'])
            ->latest()
            ->paginate(10);

        return PostResource::collection($posts);
    }
        
}













