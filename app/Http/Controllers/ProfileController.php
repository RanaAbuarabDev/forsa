<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    
    public function store(StoreRequest $request){
        $profile=Profile::create($request->validated());
        return response()->json($profile,201);
    }



    public function show($id){
        $profile=Profile::findOrFail($id);
        return response()->json($profile,200);
    }


    public function update(UpdateTaskRequest $request,$id){
        $profile=Profile::findOrFail($id);
        $profile->update($request->validated());
        return response()->json($profile,200);
    }
    
    public function delete($id){
        $profile=Profile::findOrFail($id);
        $profile->delete();
        return response()->json(null,204);
    }

    
}
