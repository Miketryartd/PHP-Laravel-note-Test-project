<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Post;
class CommentController extends Controller
{
    
   
        public function AddComment(Request $request, string $id){
            try{    
                $user = Auth::user();
                if (!$user || !Auth::check()){
                    return response()->json(["message" => "User is not logged in"], 422);
                }
                $validated = $request->validate([
                    "comment" => 'required|string|max:255',

                ]);
                $post = Post::find($id);
                
                if (!$post){
                    return response()->json(["message" => "Post does not exist"], 404);
                }
                $newComment = Comment::create(
                    ["comment" => $validated["comment"],
                     "post_id" => $post->id,
                     "user_id" => $user->id
                    ]
                );

                return response()->json(["message" => "Comment added succesfully", "comment" => $newComment], 200);


            } catch (\Exception $e){
                return response()->json(["message" => "Error adding comment", "error" => $e->getMessage()], 500);
            }
        }

}
