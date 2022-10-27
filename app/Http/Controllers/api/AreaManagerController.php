<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AreaManager;
use Illuminate\Support\Facades\Validator;

class AreaManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $area_managers = AreaManager::orderBy('id', 'DESC')->get();
        return response()->json([
            'area_managers' => $area_managers
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
            $area_manager = AreaManager::find($id);
            if($area_manager){
                return response()->json([
                    'msg' => 'success',
                    'area_manager' => $area_manager,
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
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'phone_number'=>'required',
                'area_name' => 'required',
                'zip_code' => 'required',
                'picture' => 'mimes:jpeg,jpg,png,bmp'
            ]);
 
            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first()
                ]);
            }


            $area_manager = new AreaManager();            
            $area_manager->name = $request->name;  
            $area_manager->email = $request->email; 
            $area_manager->phone_number = $request->phone_number;
            $area_manager->area_name = $request->area_name; 
            $area_manager->zip_code = $request->zip_code; 
            
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $file_extension = $file->getClientOriginalExtension();
                $uploadedFile =   (time() + 1) . '.' . $file_extension;
                $uploadDir    = public_path('tmp/images');
                $file->move($uploadDir, $uploadedFile);
                $area_manager->picture = "/tmp/images" .  $uploadedFile;
             }
            $area_manager->save();

            return response()->json([
                'msg' => 'success',
                'area_manager' => $area_manager,
            ]);
        } catch(\Exception $e){
            return response()->json([
                'msg' => 'error',
                'exception' => $e
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
             $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'phone_number'=>'required',
                'area_name' => 'required',
                'zip_code' => 'required',
                'picture' => 'mimes:jpeg,jpg,png,bmp'
            ]);
 
            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first()
                ]);
            }

            $area_manager = AreaManager::find($id);          
            
            $area_manager->name = $request->name;  
            $area_manager->email = $request->email; 
            $area_manager->phone_number = $request->phone_number;
            $area_manager->area_name = $request->area_name; 
            $area_manager->zip_code = $request->zip_code; 
            
            if ($request->hasFile('picture') && !file_exists('tmp/images/' . $request->picture)) {
                $file = $request->file('picture');
                $file_extension = $file->getClientOriginalExtension();
                $uploadedFile =   (time() + 1) . '.' . $file_extension;
                $uploadDir    = public_path('tmp/images');
                $file->move($uploadDir, $uploadedFile);
                $area_manager->picture = "/tmp/images" .  $uploadedFile;
             }
            $area_manager->save();

            return response()->json([
                'msg' => 'success',
                'manager' => $area_manager,
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
            $area_manager = AreaManager::find($id);
            if($area_manager){
                if($area_manager->picture && file_exists(public_path().'/tmp/images/' . $area_manager->picture)){
                    unlink(public_path().'/tmp/images/' . $area_manager->picture);
                }
                $area_manager->delete();
                return response()->json([
                    'msg' => 'success',
                    'area_manager' => $area_manager,
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