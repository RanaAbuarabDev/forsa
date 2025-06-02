<?php


namespace App\Http\Controllers;

use App\Services\SkillService;
use App\Services\PostFilterService;
use App\Services\UserFilterService;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use App\Http\Resources\ProfileResource;

class FilterController extends Controller
{
    protected $skillService;
    protected $postFilterService;
    protected $userFilterService;

    public function __construct(SkillService $skillService,PostFilterService $postFilterService,UserFilterService $userFilterService){
        
        $this->skillService = $skillService;
        $this->postFilterService = $postFilterService;
        $this->userFilterService = $userFilterService;
    }

    public function postFilter(Request $request)
    {
        $filters = [
            'governorate_ids' => $request->input('governorates', []),
            'job_types' => $request->input('job_types', []),
            'skill' => $request->input('skill'),
            'experience' => $request->input('experience'),
            'online' => $request->input('online'),
        ];

        $posts = $this->postFilterService->filter($filters);

        return PostResource::collection($posts);
    }

    // public function userFilter(Request $request)
    // {
    //     $filters = [
    //         'governorates' => $request->input('governorates', []),
    //         'experience' => $request->input('experience'),
    //         'skill' => $request->input('skill'),
    //     ];

    //     $users = $this->userFilterService->filter($filters);

    //     return response()->json($users);
    // }


    public function userFilter(Request $request){

        $filters = [
            'governorates' => $request->input('governorates', []),
            'experience' => $request->input('experience'),
            'skill' => $request->input('skill'),
        ];

        /** @var \Illuminate\Pagination\LengthAwarePaginator $users */
        $users = $this->userFilterService->filter($filters);

        
        $profiles = $users->getCollection()->pluck('profile')->filter();

   
        return response()->json([
            'data' => ProfileResource::collection($profiles),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }
}
