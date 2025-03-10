<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $posts=Post::all();
        return response()->json($posts,200);
    }


    public function store(StorePostRequest $request){

       
        $post=Post::create($request->validated());
        return response()->json($post,201);
    }

    public function show($id){

        $post=Post::findOrFail($id);
        return response()->json($post,200);
    }


    public function update(UpdatePostRequest $request,$id){
        $validatedData=$request->validate([
            
        ]);
        $post=Post::findOrFail($id);
        $post->update($request->validated());
        return response()->json($post,200);
    }
    
    public function delete($id){
    
        $post=Post::findOrFail($id);
        $post->delete();
        return response()->json(null,204);
    }
   
}













