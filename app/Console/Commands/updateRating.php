<?php

namespace App\Console\Commands;

ini_set('max_execution_time', '0');

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValues;
use App\Models\Review;
use App\Models\CsvTracker;
use Image;
use Illuminate\Support\Facades\File;
use \Carbon\Carbon;

class updateRating extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catalogue:rating';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Product Rating';

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
        try {
            $products = Product::get();
            $attribute = Attribute::where('code', 'rating_pdp')->first();
            foreach ($products as $key => $product) {
                if ($attribute) {
                    $attri = AttributeValues::firstOrNew(['id_product' => $product->id_product, 'id_product_attribute' => $attribute->id_product_attribute]);
                    $attri->id_product = $product->id_product;
                    $attri->id_product_attribute = $attribute->id_product_attribute;
                    $attri->value = $this->getProductReviewAndRating($product->sku);
                    $attri->value_type_integer = 0;
                    $attri->value_type_float = 0;
                    $attri->value_type_timestamp = 0;
                    $attri->save();
                }
            }
        } catch (\Exception $ex) {
            \Illuminate\Support\Facades\Log::info($ex->getMessage());
        }
    }


function getProductReviewAndRating($master_sku, $store = 1){
    $attribute = Review::where('rr_master_sku',$master_sku)->where('rr_status', 'Approved')->get();
    foreach ($attribute as $sqlResultRR) {
        $reivews['reviews'][$rr]['rr_title'] = $sqlResultRR['rr_title'];
        $reivews['reviews'][$rr]['rr_description'] = $sqlResultRR['rr_description'];
        $reivews['reviews'][$rr]['rr_rating'] = $sqlResultRR['rr_rating'];
        $reivews['reviews'][$rr]['rr_status'] = $sqlResultRR['rr_status'];
        $rr_rating += $sqlResultRR['rr_rating'];
        $reivews['total_review'] = $rr + 1;
        $average = $rr_rating / ($rr + 1);
        $reivews['average_rating'] = round($average, 2);
        $rr++;
    }

    if(!isset($reivews['average_rating'])){
        $reivews['average_rating'] = 0;
    }
    return $reivews['average_rating'] ;
}




}
