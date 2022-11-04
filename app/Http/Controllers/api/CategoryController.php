<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::orderBy('id', 'DESC')->get();
        return response()->json([
            'categories' => $categories
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
            $category = Category::find($id);
            if($category){
                return response()->json([
                    'msg' => 'success',
                    'category' => $category,
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
                'name' => 'required|string',
                'status'=>'required|string',
                'user_id' => 'exists:users,id|required|integer',
                'image' => 'mimes:jpeg,jpg,png,bmp'
            ]);
 
            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first()
                ]);
            }


            $category = new Category();            
            $category->name = $request->name; 
            $category->status = $request->status;
            $category->user_id = $request->user_id; 
            
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file_extension = $file->getClientOriginalExtension();
                $uploadedFile =   (time() + 1) . '.' . $file_extension;
                $uploadDir    = public_path('tmp/images');
                $file->move($uploadDir, $uploadedFile);
                $category->image = $uploadedFile;
             }
            $category->save();

            return response()->json([
                'msg' => 'success',
                'category' => $category,
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
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'status'=>'required|string',
                'user_id' => 'exists:users,id|required|integer',
                // 'image' => 'mimes:jpeg,jpg,png,bmp'
            ]);
 
 
            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first()
                ]);
            }

            $category = Category::find($id);          

            $category->name = $request->name; 
            $category->status = $request->status;
            $category->user_id = $request->user_id; 
            
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file_extension = $file->getClientOriginalExtension();
                $uploadedFile =   (time() + 1) . '.' . $file_extension;
                $uploadDir    = public_path('tmp/images');
                $file->move($uploadDir, $uploadedFile);
                $category->image = $uploadedFile;
             }
            $category->save();

            return response()->json([
                'msg' => 'success',
                'category' => $category,
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
            $category = Category::find($id);
            if($category){
                if($category->image && file_exists(public_path().'/tmp/images/' . $category->image)){
                    unlink(public_path().'/tmp/images/' . $category->image);
                }
                $category->delete();
                return response()->json([
                    'msg' => 'success',
                    'category' => $category,
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
