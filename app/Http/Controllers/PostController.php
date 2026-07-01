<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
class PostController extends Controller
{
    
   public function Publish(Request $request){

     try{

     $user = Auth::user();
     if (!$user || !Auth::check()){
        return response()->json(["message" => "User is not logged in"], 422);
     }

        $validator = $request->validate(
            [
                "title" => "required|string",
            "slug" => 'nullable|string',
            "subject" => 'required|string',
            "body" => 'required|string']
        );

        if (!$validator){
            return response()->json(["message" => "Missing input/credential", "input" => $validator], 404);
        }

        $slug = $validator["slug"] ?? Str::slug($validator["title"]);
     
        $newPost = Post::create([
            "user_id" => $user->id,
            "title" => $validator["title"],
            "slug" => $slug ?? null,
            "subject" => $validator["subject"],
            "body" => $validator["body"],
            
        ]);  

        return response()->json(["message" => "Post succesfully published", "post" => $newPost], 200);
         
     } catch (\Exception $e){
        return response()->json(["message" => "Error publishing post", "error" => $e->getMessage()], 500);
     }
     

     
   }

   public function GetPost(){
      try{
         $user = Auth::user();
         if (!$user){
            return response()->json(["message" => "User does not exist"], 404);
         }

         if (!$user || Auth::check()){
            return response()->json(["message" => "User is not logged in"], 422);
         }
        
         if (Post::where(["user_id" => $user->id()])){

            return response()->json(["message" => "Succesfully fetched Posts from this user"], 200);
         } else {
            return response()->json(["message" => "Error fetching posts from this user"], 500);
         }
        
      } catch (\Exception $e){
         return response()->json(["message" => "Error fetching from the user, internal error", "Error" => $e->getMessage()], 500);
      }
   }

   public function DeletePost(){

     try{
        $user = Auth::user();
        $userCheck = Auth::check();
        if (!$user || !$userCheck){
            return response()->json(["message" => "User is not logged in"], 401);
        }
        
        $postId = Post::find($user->id);
        Post::delete($postId);
        return response()->json(["message" => "Post deleted succesfully."], 200);
     } catch (\Exception $e){
        return response()->json(["message" => "Error deleting post", "error" => $e->getMessage()], 500);
     }
   }
}
