<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
   public function Search(Request $request){
    try{
        $request->validate([
            "query" => "required|string|min:2"
        ]);

        $query = $request->input("query");
        
        // Search in multiple fields
        $users = User::where("name", 'like', "%{$query}%")
                     ->orWhere("email", 'like', "%{$query}%")
                     ->paginate(10);

        return response()->json([
            "message" => "Search results", 
            "results" => $users,
            "search_term" => $query 
        ], 200);

    } catch (\Exception $e){
        return response()->json([
            "message" => "Error searching for user", 
            "error" => $e->getMessage()
        ], 500);
    }
}
}
