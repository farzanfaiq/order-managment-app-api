<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;
use PDO;

class RiderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try{
            $rider = new Rider();
            $rider->name = $request->name; 
            $rider->phone = $request->phone;
            $rider->area_name = $request->area_name; 
            
            if($request->file){
                $fileName = time().'.'.$request->file->extension();  
                $request->file->move(public_path('uploads'), $fileName);
                $rider->picture = $fileName;
             }
            $rider->save();

            return response()->json([
                'message' => 'success',
                'rider' => $rider,
            ]);
        } catch(\Exception $e){
            return response()->json([
                'message' => 'error',
                'exception' => $e
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function show(Rider $rider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function edit(Rider $rider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try{
            $rider = Rider::find($id);
            $rider->name = $request->name; 
            $rider->phone = $request->name;
            $rider->area_name = $request->name; 
            $rider->picture = $request->picture;

            $request->save();

            response("success");
        } catch(\Exception $e){
            response($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rider $rider)
    {
        //
    }
}
