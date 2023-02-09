<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Image;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BackendCategoryController extends Controller
{
    //
    public function index(Request $request){
        $category=Category::orderBy('created_at', 'desc')->get();
        return view('category.view',['category'=>$category]);
    }

    public function create(Request $request)
    {
        $id = $request['request'];
        $category=[];
        if(!empty($id)) {
            $category=Category::where("id",$id)->first();
        }
        return view('category.add', ['category'=> $category]);
    }

    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'slug' => 'required|unique:category|max:255',
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
           if($request->hasfile('image1')){
            $file=$request->file('image1');
            $filename=$this->fileHelper($file);
            $category->image1=$filename;
            }
          if($request->hasfile('image2')){
            $file=$request->file('image2');
            $filename=$this->fileHelper($file);
            $category->image2=$filename;
           }
          if($request->hasfile('image3')){
            $file=$request->file('image3');
            $filename=$this->fileHelper($file);
            $category->image3=$filename;
           }
           if ($category->save()) {
            return redirect()->back()->with('status', 'success')->with('message', 'Category Added');
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
      return response()->json([
        'errors'=>$validator->errors()->all(),
        'status'=>400,
        'message'=>"Somthing went wrong",
       ]);
     }else{
          $category =Category::find($request['category_id']);
          $category->name=$request->name;
          $category->title=$request->title;
          $category->slug=$request->slug;
          $category->description=$request->description;
          if($request->hasfile('image1')){
            $file=$request->file('image1');
            $filename=$this->fileHelper($file);
            $category->image1=$filename;
            }
          if($request->hasfile('image2')){
            $file=$request->file('image2');
            $filename=$this->fileHelper($file);
            $category->image2=$filename;
           }
          if($request->hasfile('image3')){
            $file=$request->file('image3');
            $filename=$this->fileHelper($file);
            $category->image3=$filename;
           }
        
          if ($category->save()) {
            return redirect()->back()->with('status', 'success')->with('message', 'Category Updated');
           } else {
            return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
           }
     }
    }


    public function delete(Request $request)
    {
        $category = Category::find($request['request']);
        if ($category->delete()) {
            return redirect()->back()->with('status', 'success')->with('message', 'Category Moved To Trash');
           } else {
            return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
           }
    }

    public function destroy(Request $request){
        $category=Category::withTrashed()->find($request['request']);

        if ($category->forcedelete()) {
            return redirect()->back()->with('status', 'success')->with('message', 'Category deleted');
        } else {
            return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
        }        
        
    }
    public function restore(Request $request){
        $category=Category::withTrashed()->find($request['request']);
        if ($category->restore()) {
            return redirect()->back()->with('status', 'success')->with('message', 'Category Restored Successfully');
        } else {
            return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
        }        
        
    }

    public function trashed(Request $request){
        $category=Category::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return view('category.trash',['category'=>$category]);
    }

    public function fileHelper($file)
    {
        if ($file) {
            //$destinationPath = public_path('bands/');
            $destinationPath = 'storage/category/';
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
