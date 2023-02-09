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

class updateCatalogue extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalogue:update';

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
        $csvTracker = CsvTracker::where('status', 0)->where('parent_link', null)->whereDate('schedule_date', Carbon::today())->orderBy('schedule_date','asc')->first();
        if ($csvTracker) {
            $csvTracker->status=1;
            $csvTracker->save();
            $productColumns = \Schema::connection('pim_mysql')->getColumnListing('pim_product');
            $attributeColumnsModel = Attribute::select('code')->get()->toArray();
            $attributeColumns = array_column($attributeColumnsModel, 'code');
            $file =$csvTracker->name;            
            $index = 0;
            $indexArray = array();
            $attributeIndexArray = array();
            $categoryIndexArray = array();
            $galleryIndexArray = array();
            $outputFileName='CatalogueUpdate---' . date('d M, Y h:i:s') . '.csv';
            $outputFile = public_path() . '/processing-csv/'.$outputFileName;
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
                            }
                        }

                        $output = fopen($outputFile, 'w');\Illuminate\Support\Facades\Log::info($outputFile);
                        fputcsv($output, array_merge($data, ['error']));
                    } else {
                        $checkProductDataError = $this->validateProductData($indexArray, $data);
                        if ($checkProductDataError != '') {
                            fputcsv($output, array_merge($data, [$checkProductDataError]));
                        } else {
                            $product = $this->updateProduct($indexArray, $data);
                            if ($product) {
                                $category = $this->updateCategory($product, $data[$categoryIndexArray['category']]);
                                $updateGallery = $this->updateImage($product, $data[$galleryIndexArray['gallery']]);
                                $updateAttributes = $this->updateAttributes($product, $attributeIndexArray, $data);
                                fputcsv($output, array_merge($data, ['Done']));
                            }
                        }
                    }
                    $index++;
                }
                if($outputFile!=''){
                    $csvTracker->output=$outputFileName;
                    $csvTracker->status=2;
                    $csvTracker->save();
                }
                fclose($handle);
            }
        }
    }

    public function updateProduct($indexArray, $data) {
        $checkSKU = Product::where('sku', $data[$indexArray['sku']])->first();
        if ($checkSKU) {
            $productData = array();
            foreach ($indexArray as $key => $ia) {


                if ($key != 'sku') {
                  
                    $productData[$key] = $data[$ia];
                }
                if ($key == 'parent_id') {
                    $checkParent = Product::where('sku', $data[$ia])->first();
                    $parent = ($checkParent) ? $checkParent->source_product_id : 0;
                    $productData[$key] = $parent;
                }
                if ($key == 'tax_percentage' && $data[$ia] =='') {
                    $productData[$key] = 0;
                }
                if ($key == 'image' && $data[$ia] !='') {
                    $url= $this->imageUpload($data['sku'], $data['image']);
                    $data['image'] =$url;
                }
                
            }

            $updateProduct = \DB::connection('pim_mysql')->table('pim_product')->where('sku', $data[$indexArray['sku']])->update($productData);
            return $checkSKU;
        }
        return false;
    }

    public function updateCategory($product, $data) {
        $categoriesArray = explode(',', $data);
        if (count($categoriesArray)) {
            $deleteExistingCategory = \DB::connection('pim_mysql')->table('pim_product_categories')->where('id_product', $product->source_product_id)->delete();
            foreach ($categoriesArray as $cat) {
                $checkCategory = Category::where('name', $cat)->first();
                if ($checkCategory) {
                    $insertCategory = \DB::connection('pim_mysql')->table('pim_product_categories')->insert(
                            ['id_catetory' => $checkCategory->id_category, 'id_product' => $product->source_product_id]);
                }
            }
        }
    }

    public function updateImage($product, $data) {
        $galleryImageArray = explode(',', $data);
        if (count($galleryImageArray)) {
            $oldIMages = ProductGallery::where('id_product', $product->id_product)->delete();
            foreach ($galleryImageArray as $key => $gall) {
                $url= $this->imageUpload($product->sku, $gall);
               
                $imgallery = new ProductGallery();
                $imgallery->id_product = $product->id_product;
                $imgallery->position = $key + 1;
                $imgallery->image = $url;
                $imgallery->save();
            }
        }
    }
    public function imageUpload($sku,$url) {
        
        $destinationPath = 'product/' . $sku;
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        \Illuminate\Support\Facades\Log::info("file path ".$destinationPath);
        //$filename = pathinfo($url, PATHINFO_FILENAME);
        $img = Image::make(public_path('csv_images') . '/' . $url);
        $img->resize(295, 402)->save($destinationPath . '/' . $url);
        return asset($destinationPath . '/' . $url);
    }

    public function updateAttributes($product, $attributeIndexArray, $data) {
        $productData = array();
        foreach ($attributeIndexArray as $key => $ia) {
            $attribute = Attribute::where('code', $key)->first();
            if ($attribute) {
                $attributeValue = AttributeValues::where('id_product', $product->id_product)->where('id_product_attribute', $attribute->id_product_attribute)->first();
                if ($attributeValue) {
                    $attributeValue->value = $data[$ia];
                    $attributeValue->save();
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

            if ($key == 'store') {
                if (!is_numeric($data[$ia]) || $data[$ia] < 1 || $data[$ia] != round($data[$ia])) {
                    $msg = $msg . 'The data ' . $data[$ia] . ' for the ' . $key . ' is not valid';
                }
            }

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
            
            if ($key == 'tax_percentage' && $data[$ia]!='') {
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
                   if($data[$ia]!=''){
                       $urls= explode(',', $data[$ia]);
                       foreach($urls as $url){
                           $imcheck= $this->is_image($url);
                           if($imcheck== false){
                               $msg = $msg . 'The data ' . $url . ' for the ' . $key . ' is not valid';
                           }
                       }
                   }               
            }
            if ($key == 'image') {
                   if($data[$ia]!=''){
                      $imcheck=$this->is_image($data[$ia]);
                           if($imcheck== false){
                               $msg = $msg . 'The data ' . $data[$ia] . ' for the ' . $key . ' is not valid';
                           }
                   }               
            }
        }

        return $msg;
    }
    
  public  function is_image($path)
{
	$a = getimagesize($path);
	$image_type = $a[2];
	
	if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP, IMAGETYPE_WEBP)))
	{
		return true;
	}
	return false;
}

}
