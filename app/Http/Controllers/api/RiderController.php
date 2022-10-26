<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rider;

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
        $riders = Rider::orderBy('id', 'DESC')->get();
        return response()->json([
            'riders' => $riders
        ]);
    }

   

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try{
            $rider = Rider::find($id);
            if($rider){
                return response()->json([
                    'msg' => 'success',
                    'rider' => $rider,
                ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'error',
                'exception' => $e
            ]);
        }
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
             request()->validate([
                'name' => 'required',
                'phone_number'=>'required',
                'area_name' => 'required',
                // 'photo' => 'mimes:jpeg,jpg,png,bmp'
            ]);

            $rider = new Rider();            
            $rider->name = $request->name; 
            $rider->phone = $request->phone_number;
            $rider->area_name = $request->area_name; 
            
            
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $file_extension = $file->getClientOriginalExtension();
                $uploadedFile =   (time() + 1) . '.' . $file_extension;
                $uploadDir    = public_path('tmp/images');
                $file->move($uploadDir, $uploadedFile);
                $rider->picture = $uploadedFile;
             }
            $rider->save();

            return response()->json([
                'msg' => 'success',
                'rider' => $rider,
            ]);
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'error',
                'exception' => $e->getMessage()
            ]);
        }
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
             request()->validate([
                'name' => 'required',
                'phone_number'=>'required',
                'area_name' => 'required',
                // 'photo' => 'mimes:jpeg,jpg,png,bmp'
            ]);
            $rider = Rider::find($id);          

            $rider->name = $request->name; 
            $rider->phone = $request->phone_number;
            $rider->area_name = $request->area_name; 
             $rider->save();
            
             if ($request->hasFile('photo') && !file_exists('tmp/images/' . $request->photo)) {
                $file = $request->file('photo');
                $file_extension = $file->getClientOriginalExtension();
                $uploadedFile =   (time() + 1) . '.' . $file_extension;
                $uploadDir    = public_path('tmp/images');
                $file->move($uploadDir, $uploadedFile);
                $rider->picture = $uploadedFile;
             }
            $rider->save();

            return response()->json([
                'msg' => 'success',
                'rider' => $rider,
            ]);
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'error',
                'exception' => $e
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            $rider = Rider::find($id);
            if($rider){
                if($rider->picture){
                    unlink(public_path().'/tmp/images/' . $rider->picture);
                }
                $rider->delete();
                return response()->json([
                    'msg' => 'success',
                    'rider' => $rider,
                ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'error',
                'exception' => $e
            ]);
        }
    }
}

