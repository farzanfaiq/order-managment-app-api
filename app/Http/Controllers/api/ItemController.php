<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = Item::orderBy('id', 'DESC')->get();
        return response()->json([
            'items' => $items
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
            $item = Item::find($id);
            if($item){
                return response()->json([
                    'msg' => 'success',
                    'item' => $item,
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
                'description' => 'required|string',
                'price' => 'required',
                'categroy_id'=>'required|integer',
                'user_id' => 'exists:users,id|required|integer',
                'status' => 'required|string',
                'image' => 'mimes:jpeg,jpg,png,bmp'
            ]);
 
            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first()
                ]);
            }


            $item = new Item();            
            $item->name = $request->name; 
            $item->description = $request->description;
            $item->price = $request->price; 
            $item->tax = $request->tax; 
            $item->discount = $request->discount; 
            $item->categroy_id = $request->categroy_id; 
            $item->user_id = $request->user_id; 
            $item->status = $request->status; 
            
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file_extension = $file->getClientOriginalExtension();
                $uploadedFile =   (time() + 1) . '.' . $file_extension;
                $uploadDir    = public_path('tmp/images');
                $file->move($uploadDir, $uploadedFile);
                $item->image = $uploadedFile;
             }
            $item->save();

            return response()->json([
                'msg' => 'success',
                'item' => $item,
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
                'description' => 'required|string',
                'price' => 'required',
                'categroy_id'=>'required|integer',
                'user_id' => 'exists:users,id|required|integer',
                'status' => 'required|string',
                // 'image' => 'mimes:jpeg,jpg,png,bmp'
            ]);
 
 
            if ($validator->fails()) {
                return response()->json([
                    'msg' => $validator->errors()->first()
                ]);
            }

            $item = Item::find($id);          
            $item->name = $request->name; 
            $item->description = $request->description;
            $item->price = $request->price; 
            $item->tax = $request->tax; 
            $item->discount = $request->discount; 
            $item->categroy_id = $request->categroy_id; 
            $item->user_id = $request->user_id; 
            $item->status = $request->status; 
            
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file_extension = $file->getClientOriginalExtension();
                $uploadedFile =   (time() + 1) . '.' . $file_extension;
                $uploadDir    = public_path('tmp/images');
                $file->move($uploadDir, $uploadedFile);
                $item->image = $uploadedFile;
             }
            $item->save();

            return response()->json([
                'msg' => 'success',
                'item' => $item,
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
            $item = Item::find($id);
            if($item){
                if($item->image && file_exists(public_path().'/tmp/images/' . $item->image)){
                    unlink(public_path().'/tmp/images/' . $item->image);
                }
                $item->delete();
                return response()->json([
                    'msg' => 'success',
                    'item' => $item,
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
