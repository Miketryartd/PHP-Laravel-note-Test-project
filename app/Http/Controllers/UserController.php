<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    

    public function Register(Request $request){
       
    try{
         $validator = $request->validate([
            "email" => "required|unique:users",
            "name" => "required|string",
            "role" => 'nullable|in:admin,moderator,user|string',
            "password" => "required"
        ]);

        

        $user = User::create([
            "email" => $validator["email"],
            "name" => $validator["name"],
            "role" => $validator["role"] ?? "user",
            "password" => $validator["password"]
        ]);

        return response()->json(["message" => "User registered succesfully", "user" => $user], 200);
    } catch (\Exception $e){
        return response()->json(["message" => "Interal server error", "errors" => $e->getMessage()], 500);
    }

        
    }


    public function UpdateProfile(Request $request){
      $user = Auth::user();
      if (!$user){
        return response()->json(["message" => "No user found with this id"], 404);
      }
       $validator = $request->validate([
            "name" =>  "required|string|min:8|max:255"
        ]);

      try{


        $newName = $validator["name"];
        $user->update(["name" => $newName]);
        return response()->json(["message" => "User updated succesfully", "user" => $user], 200);
       
      } catch (\Exception $e){
        return response()->json(["message" => "Internal server error updating profile", "Error" => $e->getMessage()], 500);
      }
    }

    public function UpdatePassword(Request $request){
        try{
            $validator = $request->validate(
                ["password" => "required|min:8|max:255|"]
            );
            $user = Auth::user();
            if (!$user){
                return response()->json(["message" => "user is not logged in.", "user" => $user], 404);
            }
            $newPassword = $validator["password"];
            if (Hash::check($newPassword, $user->password)){
                $hashedPassword = Hash::make($newPassword);
            }
              $user->update(["password" => $newPassword]);
             $user->tokens()->delete();

            return response()->json(["message" => "User succesfully updated password.", "user" => $user ], 200);
        } catch (\Exception $e){
            return response()->json(["message" => "Error updating password", "error" => $e->getMessage()], 500);
        }
    }

    
    public function Logout(Request $request){
        try{
            $user = Auth::user();
            if (!$user || !Auth::check()){
                return response()->json(["message" => "User is not logged in"], 422);
            }
            $user->currentAccessToken()->delete();
            return response()->json(["message" => "User logged out succesfully"], 200);
        } catch (\Exception $e){
            return response()->json(["message" => "Error logging out user", "error" => $e->getMessage()], 500);
        }
    }

    public function Profile(){

    
       
    try{
        $user = Auth::user();
        if (!$user){
            return response()->json(["message" => "User id is missing."], 404);
        }
        return response()->json(["message" => "Viewing user", "user" => $user], 200);

    } catch (\Exception $e) {
        return response()->json(["message" => "Error viewing user profile", "error" => $e->getMessage()], 500);
    }
    }

    public function Login(Request $request){
        try{
            $validator = $request->validate([
                "email" => "required|string|exists:users",
                "password"  => "required",
                "role" => "nullable|in:admin,moderator,user|string" 
            ]);

          if (  Auth::attempt(["email" => $validator["email"], "password" => $validator["password"]])){
            $user = Auth::user();
            $token = $user->createToken(("auth-token"))->plainTextToken;
            return response()->json(["message" => "User logged in succesfully", "user" => $user, "token" => $token], 200);
          } else {
            return response()->json(["message" => "Invalid credentials for this user"], 422);
          }

            
        } catch (\Exception $e){
            return response()->json(["message" => "Internal server error logging in", "error" => $e->getMessage()], 500);
        }
    }
}
