<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fichero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Resources\V1\FicheroResource;
use Illuminate\Support\Facades\Auth;
use Validator;

class FicheroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FicheroResource::collection(Fichero::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:500000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Invalid parameters',
            ], 422);
        }

        $uploadedFile = $this->uploadFile($request->file('image'));

        $fichero = Fichero::create([
            'file' => $uploadedFile['file'],
            'name' => $uploadedFile['name'],
            'user_id' => auth()->user()->id,
        ]);
        $fichero->save();
        return response()->json([
            'message' => 'Fichero created successfully!'], 201);
    }

    public function storeMassive(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'image' => 'array',
            'image.*' => 'required|image:jpeg,png,jpg,gif,svg|max:500000',            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Invalid parameters',
            ], 422);
        }
        $j = 0;
       
        foreach($request->image as $image){
            $uploadedFile = $this->uploadFile($image);
            $fichero = Fichero::create([
                'file' => $uploadedFile['file'],
                'name' => $uploadedFile['name'],
                'user_id' => auth()->user()->id,
            ]);
            $fichero->save();
            
            $j++;
        }
        
        return response()->json([
            'message' => $j . ' Fichero(s) created successfully!'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fichero  $fichero
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Fichero::find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fichero  $fichero
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fichero = Fichero::find($id);
        
        if($fichero->id)
        {
            Storage::delete('public/'.$fichero->file);
            $fichero->delete();
            return response()->json([
                'message' => 'Fichero deleted successfully'
            ], 204);
        }

        return response()->json([
            'message' => 'Fichero not found'
        ], 404);
    }

    public function uploadFile($image){    
        $image_uploaded_path = $image->store('public','local');

        return array(
            "file" => basename($image_uploaded_path),
            "name" => $image->getClientOriginalName(),          
        );
    }
}
