<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function fetchAndStoreAllPlants()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://perenual.com/api/species-list?key=sk-2sUY668f9adad9bdf6208');

        $plants = json_decode($response->getBody()->getContents(), true);
        
        if (isset($plants)) {
            foreach ($plants['data'] as $plantData) {
                $plantId = $plantData['id'];
                $response = $client->request('GET', 'https://perenual.com/api/species/details/'.$plantId.'?key=sk-2sUY668f9adad9bdf6208');
                $plantDetail = json_decode($response->getBody()->getContents(), true);

                if(isset($plantDetail)){
                    $watering = $plantDetail['watering_general_benchmark'];
                }
                Plant::firstOrCreate(
                    ['common_name' => $plantData['common_name'] ?? 'Unknown'], // Conditions de recherche
                    ['watering_general_benchmark' => $watering ?? []] // Attributs à définir si la plante n'existe pas
                );
            }

            return response()->json(['message' => 'Plants stored successfully'], 200);
        } else {
            return response()->json(['message' => 'Invalid API response'], 500);
        }
    }

    public function plantdetails($name)
    {
        $plant = Plant::where('common_name', $name)->first();

        if(isset($plant)){
            return response()->json($plant);
        }
        else{
            return response()->json(['message' => 'Plant not found'], 404);
        }
    }

    public function getAllPlants()
    {
        $plants = Plant::all();
        return response()->json($plants);
    }

    public function addPlant(Request $request)
    {
        $plant = Plant::create($request->all());
        return response()->json($plant, 201);
    }

    public function deletePlant($id){
        $plant = Plant::where('id', $id)->first();

        if(isset($plant)){
            $plant->delete();
            return response()->json(['message' => 'Plant deleted successfully'], 200);
        }
        else{
            return response()->json(['message' => 'Plant not found'], 404);
        }
    }

    
}
