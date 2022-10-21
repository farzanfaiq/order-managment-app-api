<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $managers = Manager::orderBy('id', 'DESC')->get();
        return response()->json([
            'managers' => $managers
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
            $manager = Manager::find($id);
            if($manager){
                return response()->json([
                    'msg' => 'success',
                    'manager' => $manager,
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
                'email' => 'required|email',
                'phone_number'=>'required',
                'area_name' => 'required',
                'zip_code' => 'required',
                'photo' => 'mimes:jpeg,jpg,png,bmp'
            ]);

            $manager = new Manager();            
            $manager->name = $request->name;  
            $manager->email = $request->email; 
            $manager->phone = $request->phone_number;
            $manager->area_name = $request->area_name; 
            $manager->zip_code = $request->zip_code; 
            
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $file_extension = $file->getClientOriginalExtension();
                $uploadedFile =   (time() + 1) . '.' . $file_extension;
                $uploadDir    = public_path('tmp/images');
                $file->move($uploadDir, $uploadedFile);
                $manager->picture = $uploadedFile;
             }
            $manager->save();

            return response()->json([
                'msg' => 'success',
                'manager' => $manager,
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
             request()->validate([
                'name' => 'required',
                'email' => 'required|email',
                'phone_number'=>'required',
                'area_name' => 'required',
                'zip_code' => 'required',
                'photo' => 'mimes:jpeg,jpg,png,bmp'
            ]);

            $manager = Manager::find($id);          
            
            $manager->name = $request->name;  
            $manager->email = $request->email; 
            $manager->phone = $request->phone_number;
            $manager->area_name = $request->area_name; 
            $manager->zip_code = $request->zip_code; 
            
            if ($request->hasFile('photo') && !file_exists('tmp/images/' . $request->photo)) {
                $file = $request->file('photo');
                $file_extension = $file->getClientOriginalExtension();
                $uploadedFile =   (time() + 1) . '.' . $file_extension;
                $uploadDir    = public_path('tmp/images');
                $file->move($uploadDir, $uploadedFile);
                $manager->picture = $uploadedFile;
             }
            $manager->save();

            return response()->json([
                'msg' => 'success',
                'manager' => $manager,
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
            $manager = Manager::find($id);
            if($manager){
                if(file_exists(public_path('tmp/images/' . $manager->picture))){
                    unlink(public_path().'/tmp/images/' . $manager->picture);
                }
                $manager->delete();
                return response()->json([
                    'msg' => 'success',
                    'manager' => $manager,
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
