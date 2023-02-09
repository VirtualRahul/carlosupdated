<?php

namespace App\Console\Commands;

error_reporting(E_ERROR | E_PARSE);
ini_set('max_execution_time', '0');

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValues;
use App\Models\CsvTracker;
use Image;
use Illuminate\Support\Facades\File;
use \Carbon\Carbon;
use App\Helper\CommonHelper;

class createCatalogue extends Command {

	  /* pim_product
	  pim_product_attribute_value
	  pim_product_categories
	  pim_product_gallery
	  csv_tracker
	 */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalogue:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $csvTracker='';
        try {
            $outputFile = '';

           // $CheckPreviosCsv = CsvTracker::where('status', 1)->first();
            $csvTracker = CsvTracker::where('status', 0)->where('schedule_date','<=' ,Carbon::now()->toDateTimeString())->orderBy('schedule_date', 'asc')->first();
            if ($csvTracker) {

                $csvTracker->status = 1;
                $csvTracker->save();
                if ($csvTracker->operation_type == 1) {
                    $outputFile = $this->createProductsFromCsv($csvTracker);
                } else if ($csvTracker->operation_type == 2) {
                    $outputFile = $this->createProductsFromCsv($csvTracker);
                } else if ($csvTracker->operation_type == 3) {
                    $outputFile = $this->createProductsFromCsv($csvTracker);
                    // $outputFile = $this->linkProductsToParent($csvTracker);
                } else if ($csvTracker->operation_type == 4) {
                    $outputFile = $this->createProductsFromCsv($csvTracker);
                    // $outputFile = $this->linkProductsToParent($csvTracker);
                } else if ($csvTracker->operation_type == 5) {
                    $outputFile = $this->createProductsFromCsv($csvTracker);
                }
                if ($outputFile[0] != '' && $outputFile[1] == '') {
                    $csvTracker->output = $outputFile[0];
                    $csvTracker->status = 2;
                    $csvTracker->save();
                }else{
                    $csvTracker->output = ($outputFile[0]!='')?$outputFile[0]:'';
                    $csvTracker->status = 3;
                    $csvTracker->save();
                }
            }
        } catch (\Exception $ex) {
            if($csvTracker!=''){
                $csvTracker->output = '';
                    $csvTracker->status = 3;
                    $csvTracker->error = $ex->getMessage();
                    $csvTracker->save();
            }
            \Illuminate\Support\Facades\Log::info($ex->getMessage());
        }
    }

    public function createProductsFromCsv($csvTracker) {
        $resultMsg='';
        $productColumns = \Schema::connection('pim_mysql')->getColumnListing('pim_product');
        $attributeColumnsModel = Attribute::select('code')->get()->toArray();
        $attributeColumns = array_column($attributeColumnsModel, 'code');
        $file = $csvTracker->name;
        $index = 0;
        $indexArray = array();
        $attributeIndexArray = array();
        $categoryIndexArray = array();
        $newCategoryIndexArray = array();
        $removeCategoryIndexArray = array();
        $galleryIndexArray = array();
        $outputFileName =($csvTracker->operation_type == 2)? 'CatalogueUpdate---' . date('d M, Y h:i:s') . '.csv':'CatalogueCreate---' . date('d M, Y h:i:s') . '.csv';

        $outputFile = public_path() . '/processing-csv/' . $outputFileName;
        if (($handle = fopen(public_path() . '/processing-csv/' . $file, 'r')) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($index == 0 && in_array('sku', $data)) {

                    foreach ($data as $dt) {
                        if (in_array($dt, $productColumns)) {
                            $indexArray[$dt] = array_search($dt, $data);
                        } else if (in_array($dt, $attributeColumns)) {
                            $attributeIndexArray[$dt] = array_search($dt, $data);
                        } else if ($dt == 'gallery') {
                            $galleryIndexArray[$dt] = array_search($dt, $data);
                        } else if ($dt == 'category') {
                            $categoryIndexArray[$dt] = array_search($dt, $data);
                        } else if ($dt == 'add_new_category') {
                            $newCategoryIndexArray[$dt] = array_search($dt, $data);
                        }

                        else if ($dt == 'remove_category') {
                            $removeCategoryIndexArray[$dt] = array_search($dt, $data);
                        }


                    }

                    $output = fopen($outputFile, 'w');
                    fputcsv($output, array_merge($data, ['error']));
                } else {
                    $checkProductDataError = $this->validateProductData($indexArray, $data);
                    $checkGalleryDataError = $this->validateProductData($galleryIndexArray, $data);
                    if ($checkProductDataError != '') {
                        fputcsv($output, array_merge($data, [$checkProductDataError]));
                        $resultMsg=$resultMsg.$checkProductDataError;
                    }
                    else if ( $checkGalleryDataError != '') {
                        fputcsv($output, array_merge($data, [$checkGalleryDataError]));
                        $resultMsg=$resultMsg.$checkGalleryDataError;
                    }
                    else {
                        try {
                            $product = $this->updateProduct($indexArray, $data);
                            if ($product) {

                                if($data[$indexArray['image']]!=''){
                                    $mainImage= $this->updateProductImage($product,$data[$indexArray['image']]);
                                }
                                if($data[$galleryIndexArray['gallery']]!=''){
                                    $updateGallery = $this->updateImage($product, $data[$galleryIndexArray['gallery']]);
                                }
                                if($data[$categoryIndexArray['category']]!=''){
                                    $category = $this->updateCategory($product, $data[$categoryIndexArray['category']]);
                                }

                                if($data[$newCategoryIndexArray['add_new_category']]!=''){
                                    $addcategory = $this->addNewCategory($product, $data[$newCategoryIndexArray['add_new_category']]);
                                }

                                if($data[$removeCategoryIndexArray['remove_category']]!=''){
                                    $removecategory = $this->removeCategory($product, $data[$removeCategoryIndexArray['remove_category']]);
                                }

                                $updateAttributes = $this->updateAttributes($product, $attributeIndexArray, $data);
                                fputcsv($output, array_merge($data, ['Done']));
                            }
                        } catch (\Exception $ex) {
                            $resultMsg=$resultMsg.$ex->getMessage();
                            fputcsv($output, array_merge($data, [$ex->getMessage()]));
                            \Illuminate\Support\Facades\Log::info($ex->getMessage());
                        }


                    }
                }
                $index++;
            }

            fclose($handle);
            return [$outputFileName,$resultMsg];
        }
    }



    public function linkProductsToParent($csvTracker) {
        $resultMsg='';
        $file = $csvTracker->name;
        $index = 0;
        $outputFileName = 'CatalogueParentLink---' . date('d M, Y h:i:s') . '.csv';
        $outputFile = public_path() . '/processing-csv/' . $outputFileName;
        if (($handle = fopen(public_path() . '/processing-csv/' . $file, 'r')) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($index == 0 && in_array('sku', $data) && in_array('parent_sku', $data)) {
                    $output = fopen($outputFile, 'w');
                    fputcsv($output, array_merge($data, ['error']));
                } else {
                    $errorMsg='';
                    $parentSku = Product::where('sku', $data[1])->first();
                    $product = Product::where('sku', $data[0])->first();
                    if (!$parentSku) {
                        $errorMsg='Parent SKU not found';
                    }
                    if($parentSku){
                       if($parentSku->parent_id){
                          $errorMsg='Parent SKU cant have parent';
                       }
                    }
                    if (!$parentSku) {
                        $errorMsg='SKU not found';
                    }
                    if($product){
                       if(count($product->childs)){
                          $errorMsg='Child SKU Cant be parent';
                       }
                    }
                    if($data[1]==$data[0]){
                        $errorMsg='Both sku cant be same';
                    }
                    if($errorMsg!=''){
                        $resultMsg=$resultMsg.$errorMsg;
                        fputcsv($output, array_merge($data, [$errorMsg]));
                    }else{
                        if ($parentSku && $product) {
                        $sizeId = 0;
                        $sizeArray = Attribute::where('code', 'size')->first();
                        if ($sizeArray) {
                            $sizeId = $sizeArray->id_product_attribute;
                        }
                        $SkuAttribute = AttributeValues::where('id_product', $product->id_product)->where('id_product_attribute', $sizeId)->first();
                        if ($SkuAttribute) {

                            $product->configrable_atribute_code = '';
                            $product->configrable_atribute_value = $SkuAttribute->value;
                            $product->save();
                        }

                        $product->parent_id = $parentSku->source_product_id;
                        $product->save();
                        $parentSku->has_child='yes';
                        $parentSku->quantity=0;
                        $parentSku->save();
                        $parentSizes= $this->updateParentSize($parentSku,$sizeId);

                        $parentAttribute = AttributeValues::where('id_product', $parentSku->id_product)->where('id_product_attribute', $sizeId)->first();
                        if ($parentAttribute) {
                            $parentAttribute->value = $parentSizes;
                            $parentAttribute->save();
                        } else {
                            $parentAttribute = new AttributeValues();
                            $parentAttribute->id_product = $parentSku->id_product;
                            $parentAttribute->id_product_attribute = $sizeId;
                            $parentAttribute->value = $parentSizes;
                            $parentAttribute->value_type_integer = 0;
                            $parentAttribute->value_type_float = 0;
                            $parentAttribute->value_type_timestamp = 0;
                            $parentAttribute->save();
                        }
                        $allSiblingsQuantity = Product::where('parent_id', $product->parent_id)->sum('quantity');
                        $stockStatus = 'in-stock';
                        if ($allSiblingsQuantity < 1) {
                            $stockStatus = 'out-of-stock';
                        }

                        if ($parentSku->stock_status != $stockStatus) {
                            $parentSku->stock_status = $stockStatus;
                            $parentSku->save();
                        }
                        fputcsv($output, array_merge($data, ['Done']));
                    }
                    }

                }
                $index++;
            }
            fclose($handle);
            return [$outputFileName,$resultMsg];
        }
    }

    public function updateParentSize($parentSku, $sizeId) {
        $sizeArray = array();
        $child = Product::where('parent_id', $parentSku->source_product_id)->get();
        if (count($child)) {
            foreach ($child as $cd) {
                $checkAttribute = AttributeValues::where('id_product', $cd->id_product)->where('id_product_attribute', $sizeId)->first();
                if ($checkAttribute) {
                    $sizeArray[] = '[' . $checkAttribute->value . ']';
                }
            }
        }
        return trim(preg_replace("/,+/", ",", implode($sizeArray, ',')), ',');
    }

    public function updateProduct($indexArray, $data) {
        $productData = array();
        $stockStatus = 'out-of-stock';
        foreach ($indexArray as $key => $ia) {
            $productData[$key] = $data[$ia];
            if ($key == 'tax_percentage' && $data[$ia] == '') {
                $productData[$key] = 0;
            }
            if ($key == 'quantity' && $data[$ia] > 0) {
                $stockStatus = 'in-stock';
            }
        }
        if(isset($indexArray['quantity'])){
           $productData['stock_status'] = $stockStatus;
        }

        $checkSKU = Product::where('sku', $data[$indexArray['sku']])->first();
        if ($checkSKU) {
            $newProductData = array();
            if (count($productData)) {
                foreach ($productData as $key => $pro) {
                    $mainvalue = preg_replace('/\s+/', '', $pro);
                    if ($mainvalue != '') {
                        $newProductData[$key] = $pro;
                    }
                }
            }

            $updateProduct = \DB::connection('pim_mysql')->table('pim_product')->where('sku', $data[$indexArray['sku']])->update($newProductData);
            return $checkSKU;
        } else {
            $productData['parent_id'] = 0;
            $productData['configrable_atribute_code'] = 'size';
            $productData['has_child'] = 'no';
            $updateProduct = Product::create($productData);
            $updateProduct = Product::find($updateProduct->id_product);
            $updateProduct->source_product_id = $updateProduct['id_product'];
            $updateProduct->save();

            return $updateProduct;
        }
        return false;
    }

    public function updateCategory($product, $data) {
        \Illuminate\Support\Facades\Log::info('category data '.$data);
        // $categoriesArray = explode(',', $data);
        $categoriesArray = explode('-', $data);
        if (count($categoriesArray)) {
            $deleteExistingCategory = \DB::connection('pim_mysql')->table('pim_product_categories')->where('id_product', $product->source_product_id)->delete();
            foreach ($categoriesArray as $cat) {
                $checkCategory = Category::find($cat);
                if ($checkCategory) {
                    $insertCategory = \DB::connection('pim_mysql')->table('pim_product_categories')->insert(
                            ['id_catetory' => $checkCategory->id_category, 'id_product' => $product->source_product_id]);
                }
            }
        }
    }

    public function addNewCategory($product, $data) {
        \Illuminate\Support\Facades\Log::info('add new category data '.$data);
        // $categoriesArray = explode(',', $data);
        $categoriesArray = explode('-', $data);
        if (count($categoriesArray)) {
            foreach ($categoriesArray as $cat) {
                $checkCategory = Category::find($cat);
                if ($checkCategory) {
                    $insertCategory = \DB::connection('pim_mysql')->table('pim_product_categories')->insert(
                            ['id_catetory' => $checkCategory->id_category, 'id_product' => $product->source_product_id]);
                }
            }
        }
    }

    public function removeCategory($product, $data) {
        \Illuminate\Support\Facades\Log::info('category data '.$data);
        // $categoriesArray = explode(',', $data);
        $categoriesArray = explode('-', $data);
        if (count($categoriesArray)) {
            $deleteExistingCategory = \DB::connection('pim_mysql')->table('pim_product_categories')->where('id_product', $product->source_product_id)->whereIn('id_catetory', $categoriesArray)->delete();
        }
    }

    public function updateImage($product, $data) {
        $galleryImageArray = explode(',', $data);
        if (count($galleryImageArray)) {
            $oldIMages = ProductGallery::where('id_product', $product->id_product)->delete();
            /* $imagePath = public_path("product/$product->sku");

            if (File::isDirectory($imagePath)) {
                system("rm -rf ".escapeshellarg($imagePath));
            } */

            foreach ($galleryImageArray as $key => $gall) {
                if($gall!=''){
                     $url = $this->imageUpload($product->sku, $gall,2);

                $imgallery = new ProductGallery();
                $imgallery->id_product = $product->id_product;
                $imgallery->position = $key + 1;
                $imgallery->image = $url;
                $imgallery->save();
                }

            }
        }
    }
    public function updateProductImage($product,$localImage) {
        if ($product!='' && $localImage!='') {
                $url = $this->imageUpload($product->sku, $localImage,1);

                $updateProduct = Product::find($product->id_product);
                if($updateProduct){
                    $updateProduct->image=$url;
                    $updateProduct->save();
                    \Illuminate\Support\Facades\Log::info('product image path '.$url);
                }
        }
    }

	public function imageUpload($sku, $url, $type) {
        // dd($sku, $url, $type);
        $firstIndex = explode(".", $url);
        $imageName = isset($firstIndex[0]) ? $firstIndex[0]."_".mt_rand(1111,9999).".webp" : $url;
        //$imageName = isset($firstIndex[0]) ? $firstIndex[0].".webp" : $url;
        \Illuminate\Support\Facades\Log::info('update image path ' . $sku . '   ' . $url);
        $destinationPath = public_path('product/' . $sku);
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        if (!File::isDirectory($destinationPath . '/300')) {
            File::makeDirectory($destinationPath . '/300', 0777, true, true);
        }
        if (!File::isDirectory($destinationPath . '/665')) {
            File::makeDirectory($destinationPath . '/665', 0777, true, true);
        }
        $img_300 = Image::make(public_path('csv_images') . '/' . $url);
        $img_300->resize(479, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/300/' . $imageName);

        $img_665 = Image::make(public_path('csv_images') . '/' . $url);
        $img_665->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/665/' . $imageName);
        $mainPath='product/' . $sku . '/665/' . $imageName;
        if($type==1){
            $mainPath='product/' . $sku . '/300/' . $imageName;
        }

        $imageUrl = env('GUMLET_PRE_URL').$mainPath;
//        CommonHelper::gumletCacheClear($imageUrl);
        return $imageUrl;
        return asset($mainPath);
    }




    public function updateAttributes($product, $attributeIndexArray, $data) {
        $productData = array();
        foreach ($attributeIndexArray as $key => $ia) {
            if (isset($data[$ia])) {
                $attribute = Attribute::where('code', $key)->first();
                if ($attribute) {
                    $attri = AttributeValues::firstOrNew(['id_product' => $product->id_product, 'id_product_attribute' => $attribute->id_product_attribute]);
                    $attri->id_product = $product->id_product;
                    $attri->id_product_attribute = $attribute->id_product_attribute;
                    $attri->value = $data[$ia];
                    $attri->value_type_integer = 0;
                    $attri->value_type_float = 0;
                    $attri->value_type_timestamp = 0;
                    $attri->save();
                    if ($product->parent_id != '' && $attribute->code == 'size') {
                        $oldSize = $product->configrable_atribute_value;
                        $product->configrable_atribute_value = $attri->value;
                        $product->save();
                        $parentSku = Product::where('source_product_id', $product->parent_id)->first();
                        if ($parentSku) {
                            $parentAttribute = AttributeValues::where('id_product', $parentSku->id_product)->where('id_product_attribute', $attribute->id_product_attribute)->first();
                            if ($parentAttribute) {
                                $existingSizes = $parentAttribute->value;
                                $parentAttribute->value = trim(trim($this->removeSize($existingSizes, '[' . $oldSize . ']'), ',') . ',[' . $product->configrable_atribute_value . ']', ',');
                                $parentAttribute->save();
                            }
                        }
                    }
                }
            }
        }
    }

    public function validateDate($date, $format = 'Y-m-d') {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function validateProductData($indexArray, $data) {
        $msg = '';
        foreach ($indexArray as $key => $ia) {

            if ($key == 'quantity') {
                if (!is_numeric($data[$ia]) || $data[$ia] < 0 || $data[$ia] != round($data[$ia])) {
                    $msg = $msg . 'The data ' . $data[$ia] . ' for the ' . $key . ' is not valid';
                }
            }
            if ($key == 'price' || $key == 'selling_price') {
                if (!filter_var($data[$ia], FILTER_VALIDATE_FLOAT) || $data[$ia] < 1) {
                    $msg = $msg . 'The data ' . $data[$ia] . ' for the ' . $key . ' is not valid';
                }
            }
            if ($key == 'url_key') {
                $checkUrl=Product::where('url_key',$data[$ia])->where('sku','!=',$data[$indexArray['sku']])->first();
                if($checkUrl){
                    $msg = $msg . 'The data ' . $data[$ia] . ' for the ' . $key . ' is not valid';
                }
            }

            if ($key == 'tax_percentage' && ($data[$ia] != '' || $data[$ia] != 0)) {
                if (!filter_var($data[$ia], FILTER_VALIDATE_FLOAT) || $data[$ia] < 1) {
                    $msg = $msg . 'The data ' . $data[$ia] . ' for the ' . $key . ' is not valid';
                }
            }

            if ($key == 'selling_price_from_date' || $key == 'selling_price_to_date') {

                if (!$this->validateDate($data[$ia])) {
                    $msg = $msg . 'The data ' . $data[$ia] . ' for the ' . $key . ' is not valid';
                }
            }
            if ($key == 'gallery') {
                if ($data[$ia] != '') {
                    $urls = explode(',', $data[$ia]);
                    foreach ($urls as $url) {
                        $imcheck = $this->is_image($url);
                        if ($imcheck == false) {
                            $msg = $msg . 'The data ' . $url . ' for the ' . $key . ' is not valid';
                        }
                    }
                }
            }
            if ($key == 'image') {
                if ($data[$ia] != '') {
                    $imcheck = $this->is_image($data[$ia]);
                    if ($imcheck == false) {
                        $msg = $msg . 'The data ' . $data[$ia] . ' for the ' . $key . ' is not valid';
                    }
                }
            }
        }

        return $msg;
    }

    public function is_image($path) {
        $a = getimagesize(public_path('csv_images') . '/' . $path);
        $image_type = $a[2];
        if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP, IMAGETYPE_WEBP))) {
            return true;
        }
        return false;
    }

    public function removeSize($size,$key) {
        $sizes= explode(',', $size);
        $this->remove_element($sizes,$key);
        return implode(',',$sizes);
    }

    public function remove_element(&$array,$value) {
    if(($key = array_search($value,$array)) !== false) {
        unset($array[$key]);
    }
}
}
