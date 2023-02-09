<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    //
    public function index(Request $request){
        $category=Category::all();
        return response()->json([
           'category'=>$category
        ]);
    }

    public function add(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
       ]);
     if ($validator->fails())
     {
      return response()->json([
        'errors'=>$validator->errors()->all(),
        'status'=>400,
        'message'=>"Somthing went wrong",
       ]);
     }else{
           $category = new Category;
           $category->name=$request->name;
           $category->title=$request->title;
           $category->slug=$request->slug;
           $category->description=$request->description;
        
        
         if($category->save()){
              return response()->json([
                  'status'=>200,
                  'category'=>$category,
                  'message'=>"Category Added Successfully",
               ]);
         }else{
          return response()->json([
                 'status'=>401,
                 'message'=>"Somthing went wrong",
           ]);
        }
     }
    }

    public function update(Request $request){
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
       ]);
     if ($validator->fails())
     {
      return response()->json([
        'errors'=>$validator->errors()->all(),
        'status'=>400,
        'message'=>"Somthing went wrong",
       ]);
     }else{
          $category =Category::find($request['product_id']);
          $category->name=$request->name;
          $category->title=$request->title;
          $category->slug=$request->slug;
          $category->description=$request->description;
        
         if($category->save()){
              return response()->json([
                  'status'=>200,
                  'category'=>$category,
                  'message'=>"Category Updated Successfully",
               ]);
         }else{
          return response()->json([
                 'status'=>401,
                 'message'=>"Somthing went wrong",
           ]);
        }
     }
    }


    public function delete(Request $request)
    {
        $category = Category::find($request['request']);
       if ($category->delete()) {
             return response()->json([
                'status'=>200,
                'message'=>"Category Deleted",
             ]);
        }else{
            return response()->json([
               'status'=>401,
               'message'=>"Somthing went wrong",
            ]);
        }
    }

    public function filter(Request $request){

    }

    // public function destroy(Request $request){
    //     $product=Product::withTrashed()->find($request['request']);

    //     if ($product->forcedelete()) {
    //         return response()->json([
    //             'status'=>200,
    //             'message'=>"Product Deleted Successfully",
    //          ]);
    //     } else {
    //         return response()->json([
    //             'status'=>401,
    //             'message'=>"Something went wrong",
    //          ]);
    //     }        
        
    // }
    // public function restore(Request $request){
    //     $product=Product::withTrashed()->find($request['request']);
    //     if ($product->restore()) {
    //         return response()->json([
    //             'status'=>200,
    //             'message'=>"Product Restored Successfully",
    //          ]);
            
    //     } else {
    //         return response()->json([
    //             'status'=>401,
    //             'message'=>"Something went wrong",
    //          ]);
    //     }        
        
    // }

    // public function trashed(Request $request){
    //     $products=Product::onlyTrashed()->get();
    //     return response()->json([
    //         'status'=>200,
    //         'products'=>$products,
    //      ]);
    // }

}
