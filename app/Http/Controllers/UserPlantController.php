<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserPlantController extends Controller
{
    public function addUserPlant(Request $request)
    {
        $name = $request->input('name');
        $city = $request->input('city');

        $user = auth()->user();
        $plant = Plant::where('common_name', $name)->first();

        if(isset($plant)){
            $cacheKey = 'city_' . $user->id;

            if ($city) {
                Cache::put($cacheKey, $city, 120);
                $cachedCity = $city;
            } else {
                $cachedCity = Cache::get($cacheKey, 'Paris');
            }
            
            $user->plants()->attach($plant->id);
            return response()->json(['message' => 'Plant added to user'.$cachedCity], 200);
        }
        else{
            return response()->json(['message' => 'Plant not found'], 404);
        }
    }

    public function deleteUserPlant($id)
    {
        $user = auth()->user();
        $plant = Plant::find($id);

        if($plant){
            $user->plants()->detach($plant->id);
            return response()->json(['message' => 'Plant removed from user'], 200);
        }
        else{
            return response()->json(['message' => 'Plant not found'], 404);
        }
    }
}
