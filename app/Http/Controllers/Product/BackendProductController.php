<?php
namespace App\Http\Controllers\Product;


use App\Models\Product;
use App\Models\Category;
use Image;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BackendProductController extends Controller
{
    //
    public function index(Request $request){
        $products=Product::orderBy('created_at', 'desc')->get();
        $category=Category::orderBy('created_at', 'desc')->get();
        if($request->ajax()){
            if(isset($request->from) && isset($request->to) && isset($request->cat)){
        
                
                $data = Product::whereDate('created_at','<=',$request->to)
                                 ->whereDate('created_at','>=',$request->from)
                                 ->where('category_id',$request->cat)
                                 ->orderBy('created_at', 'desc')
                                 ->get();
                 return response()->json([
                     'products'=>$data,
                 ]);
            }elseif(!empty($request->from) && !empty($request->to)){
        
                
                   $data = Product::whereDate('created_at','<=',$request->to)
                                    ->whereDate('created_at','>=',$request->from)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                    return response()->json([
                        'products'=>$data,
                        'cat'=>$request->cat

                    ]);
            }elseif(isset($request->cat)){
                $products=Product::where('category_id',$request->cat)->orderBy('created_at', 'desc')->get();
                    return response()->json([
                      'products'=>$products
                     
                ]);
            }else{
                  $products=Product::orderBy('created_at', 'desc')->get();
                     return response()->json([
                       'products'=>$products
                ]);
              
            }
            
        }
        return view('product.view',['products'=>$products,'category'=>$category]);
    }

    public function create(Request $request)
    {
        $id = $request['request'];
        $product = [];
        $actualcat=[];
        if(!empty($id)) {
            $product= Product::find($id);
            $cat_id=$product->category_id;
            $actualcat=Category::where("id",$cat_id)->first();
            
        }
        return view('product.add', ['product'=>$product,'actualcat'=> $actualcat]);
    }

    public function trashed(Request $request){
        $products=Product::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        $category=Category::all();
        if($request->ajax()){
            if(isset($request->cat)){
                $products=Product::onlyTrashed()->where('category_id',$request->cat)->orderBy('created_at', 'desc')->get();
                    return response()->json([
                      'products'=>$products
                ]);
              }else{
                  $products=Product::onlyTrashed()->orderBy('created_at', 'desc')->get();
                     return response()->json([
                       'products'=>$products
                ]);
              }
        }
        return view('product.trash',['products'=>$products,'category'=>$category]);
    }

    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',
       ]);
     if ($validator->fails())
     {
        return redirect()->back()
        ->withErrors($validator)
        ->withInput();
     }else{
           $product = new Product;
           $product->name=$request->name;
           $product->category_id=$request->category;
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
        
           if ($product->save()) {
            return redirect()->back()->with('status', 'success')->with('message','Product Added');
        } else {
            return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
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
        return redirect()->back()
        ->withErrors($validator)
        ->withInput();
     }else{
          $product =Product::find($request['product_id']);
          $product->name=$request->name;
          $product->category_id=$request->category;
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
          if ($product->save()) {
            return redirect()->back()->with('status', 'success')->with('message','Product Updated');
        } else {
            return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
        }
     }
    }


    public function delete(Request $request)
    {
        $product = Product::find($request['request']);
       if ($product->delete()) {
           return redirect()->back()->with('status', 'success')->with('message', 'Product Moved To Trash');
        }else{
            return response()->json([
               'status'=>401,
               'message'=>"Somthing went wrong",
            ]);
        }
    }

    public function destroy(Request $request){
        $product=Product::withTrashed()->find($request['request']);

        if ($product->forcedelete()) {
            return redirect()->back()->with('status', 'success')->with('message', 'Product deleted');
        } else {
            return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
        }        
        
    }
    public function restore(Request $request){
        $product=Product::withTrashed()->find($request['request']);
        if ($product->restore()) {
            return redirect()->back()->with('status', 'success')->with('message', 'Product Restored Successfully');
        } else {
            return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
        }        
        
    }

    public function getCategory(Request $request){
        $response = array();
        $search = $request->name;
       
        if ($request->ajax()) {
            if(empty($search)){
                $response =''; 
                return $response; 
            }else{
                $category = Category::where('name', 'like', '%' . $search . '%')->orderBy('created_at', 'desc')->get();
                  if(!empty($category[0])){
                        $response ='<select class="form-group" style="display:block;position:relative;z-index=1;cursor:pointer;">';
                               foreach($category as $cat){
                                     $response .='<option class="cat_name" value='.$cat->id.'>'.$cat->name.'</option>';  
                                }
                        $response .='</select>';
                  }else{
                        $response ='<ul class="list-group" style="display:block;position:relative;z-index=1"><li class="list-group-item">No results found</li></ul>'; 
                         return $response; 
                  }
            return $response;
        }
      }

    }


    public function getTags(Request $request){
        $response = array();
        $search = $request->search;
            if($search != ""){
                $response = Category::where('name', 'like', '%' . $search . '%')->orderBy('created_at', 'desc')->get();
                return response()->json($response);
            }
            return response()->json($response);
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
