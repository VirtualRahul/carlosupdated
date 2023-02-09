<?php

namespace App\Http\Controllers\Product;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index(Request $request){
        $products=Product::orderBy('created_at', 'desc')->get();
        return response()->json([
           'products'=>$products
        ]);
    }

    public function create(Request $request)
    {
        $id = $request['request'];
        $product = [];
        if(!empty($id)) {
            $product= Product::find($id);
        }
          return response()->json([
            'product'=>$product
         ]);
    }

    public function save(Request $request){
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
           $product = new Product;
           $product->name=$request->name;
           $product->category_id=$request->category_id;
           $product->title=$request->title;
           $product->description=$request->description;
           if($request->hasfile('image1')){
            $file=$request->file('image1');
            $filename=$this->fileHelper($file);
            $product->image1=$filename;
            }
          if($request->hasfile('image2')){
            $file=$request->file('image2');
            $filename=$this->fileHelper($file);
            $product->image2=$filename;
           }
          if($request->hasfile('image3')){
            $file=$request->file('image3');
            $filename=$this->fileHelper($file);
            $product->image3=$filename;
           }
         if($product->save()){
              return response()->json([
                  'status'=>200,
                  'product'=>$product,
                  'message'=>"Product Added Successfully",
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
          $product =Product::find($request['product_id']);
          $product->name=$request->name;
          $product->category_id=$request->category_id;
          $product->title=$request->title;
          $product->description=$request->description;

          if($request->hasfile('image1')){
            $file=$request->file('image1');
            $filename=$this->fileHelper($file);
            $product->image1=$filename;
          }
          if($request->hasfile('image2')){
            $file=$request->file('image2');
            $filename=$this->fileHelper($file);
            $product->image2=$filename;
          }
          if($request->hasfile('image3')){
            $file=$request->file('image3');
            $filename=$this->fileHelper($file);
            $product->image3=$filename;
          }
         if($product->save()){
              return response()->json([
                  'status'=>200,
                  'product'=>$product,
                  'message'=>"Product Updated Successfully",
               ]);
         }else{
          return response()->json([
                 'status'=>401,
                 'message'=>"Somthing went wrong",
           ]);
        }
     }
    }


    public function delete()
    {
        $request = json_decode(file_get_contents('php://input'), true);
        $product = Product::find($request['id']);
       if ($product->delete()) {
             return response()->json([
                'status'=>200,
                'message'=>"Product Moved To Trash",
             ]);
        }else{
            return response()->json([
               'status'=>401,
               'message'=>"Somthing went wrong",
            ]);
        }
    }

    public function destroy(){
        $request = json_decode(file_get_contents('php://input'), true);
        $product=Product::withTrashed()->find($request['id']);

        if ($product->forcedelete()) {
            return response()->json([
                'status'=>200,
                'message'=>"Product Deleted Successfully",
             ]);
        } else {
            return response()->json([
                'status'=>401,
                'message'=>"Something went wrong",
             ]);
        }        
        
    }
    public function restore(){
        $request = json_decode(file_get_contents('php://input'), true);
        $product=Product::withTrashed()->find($request['id']);
        if ($product->restore()) {
            return response()->json([
                'status'=>200,
                'message'=>"Product Restored Successfully",
             ]);
            
        } else {
            return response()->json([
                'status'=>401,
                'message'=>"Something went wrong",
             ]);
        }        
        
    }

    public function trashed(Request $request){
        $products=Product::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status'=>200,
            'products'=>$products,
         ]);
    }

    public function fileHelper($file)
    {
        if ($file) {
            //$destinationPath = public_path('bands/');
            $destinationPath = 'storage/products/';
            //print_r($destinationPath); exit;
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $mainimg = time() . $file->getClientOriginalName();
            $ext = explode(".", $mainimg);
            $img = Image::make($file);
            //$img->resize(295, 402)->save($destinationPath . '/' . $mainimg);
            $img->save(
                $destinationPath . "/" . $ext[0] . '.webp'
            );

            $mainPath = $ext[0].'.webp';
            $imageUrl = $destinationPath . $mainPath;
            return $imageUrl;
            // return asset('cms_images/' . $ext[0] . '.webp');
        }
    }


}
