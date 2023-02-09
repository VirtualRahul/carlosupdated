<?php

namespace App\Helper;

use App\Models\Quote;
use App\Models\OrderItems;
use App\Models\Customer;
use App\Models\Address;


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommonHelper
 *
 * @author yatin
 */
class CommonHelper {

    static function sendRequest($url, $method, $data) {
        $res = false;
        $resp_data = '';
        $data = (count($data)) ? json_encode($data) : '';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $resp_data = $err;
        } else {
            $res = true;
            $resp_data = $response;
        }
        return [$res, $resp_data];
    }

    static function validateCoupon($quote, $products, $coupon) {
        $store = ($quote->store == 1) ? 'w' : 'aurelia';
        $productArray = array();
        foreach ($products as $product) {
            $discount = 'no';
            if ($product->selling_price < $product->price) {
                $discount = 'yes';
            }
            $productArray[$product->master_sku] = ['sku' => $product->master_sku, 'category' => '', 'qty' => $product->qty, 'final_price' => $product->price, 'discounted' => $discount];
        }
        $productData['coupon_code'] = $coupon;
        $productData['store'] = $store;
        $productData['payment_method'] = ($quote->payment_method == null) ? 'cod' : $quote->payment_method;
        $productData['products'] = $productArray;
        $couponData = ['request' => json_encode($productData)];

        $res = false;
        $resp_data = '';
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        $url_uc = env('PROMOTION_URL');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url_uc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $couponData,
            CURLOPT_HTTPHEADER => array(
                "Postman-Token: 970f30a1-56dd-4b8b-a655-cfbb805ce148",
                "cache-control: no-cache",
                "content-type: multipart/form-data"
            ),
        ));

        $response = curl_exec($curl);      
      //echo $response;exit();
       // \Illuminate\Support\Facades\Log::channel('slack')->error('coupon test ' .$response );
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $resp_data = $err;
        } else {
            $res = true;
            $resp_data = $response;
        }
        return [$res, $resp_data];
    }


   

    public static function blockInventory($quote) {
        $productData = '';
        if (count($quote->products)) {
            foreach ($quote->products as $product) {
                $productData = $productData . ',' . $product->sku . '_' . $product->qty;
            }
        }
        $url = env('PIMURL').'/pim/pimresponse.php/?service=stock&store=' . $quote->store . '&items=' . trim($productData, ",");
        $responseData = self::sendRequest($url, 'GET', array());
        if ($responseData[0]) {
            $response = json_decode($responseData[1], true);
            if ($response['response']['error']) {
                self::blockInventory($quote);
            }
        } else {
            self::blockInventory($quote);
        }
    }

    
    
     public static function checkInventory($quote) {
        $name = '';
        $res = false;
        $actulaProduct= OrderItems::where('order_quote_id',$quote->order_quote_id)->get();
        $productCount=count($actulaProduct);
        $validInventory=0;
        if ($productCount) {
            foreach ($actulaProduct as $ca) {
                $name = $ca->name.', '.$name;
                $response = self::facilityChecker($ca->sku, $ca->qty, $quote->order_quote_id,$quote->store);
                if ($response[0]) {
                    $noitem = 0;
                    $res_data = json_decode($response[1], true); 
                    if(isset($res_data['successful'])){
                        if ($res_data['successful'] == true) {
                        if ($res_data['inventorySnapshots'][0]['inventory'] >= $ca->qty) {
                            $validInventory++;
                        }else{
                            //$name = $ca->name.', '.$name;
                        }
                    }
                    }else{
                        return [true, rtrim($name, ', ')];
                        
                    }
                    
                } else {
                    return [true, rtrim($name, ', ')];
                }
            }
        }
        
        if($productCount==$validInventory){
            $res=true;
        }

        return [$res, rtrim($name, ', ')];
    }
    
     public static function facilityChecker($sku, $qty, $cart_id,$store) {
        $firstcode=($store==1)?'TCNS_113':'Glaucus';
        $secondcode=($store==1)?'Glaucus':'TCNS_113';
        $thirdcode='BG_GL_01';
        $codes = [$firstcode, $secondcode, $thirdcode];
        $firstCheck=array();
        $token = self::getUCToken($cart_id);
        if($token!=''){
            foreach ($codes as $code) {
            $firstCheck = self::callInventoryUC($sku, $code, $cart_id,$token);
            if ($firstCheck[0]) {
                $res_data = json_decode($firstCheck[1], true); 
                if(isset($res_data['successful'])){
                if ($res_data['successful'] == true) {
                    if ($res_data['inventorySnapshots'][0]['inventory'] > $qty) {
                        break;
                    }
                }
                }
            }
        }
        }else{
            $firstCheck[0]=false;
        }
        
        
        return $firstCheck;
    }
    public static function getUCToken($cart_id) {
        $resp_data = '';
        $token = '';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://tcns.unicommerce.com/oauth/token?grant_type=password&client_id=my-trusted-client&username=varun.verghese@tcnsclothing.com&password=Necromancer2787",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HEADER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "Postman-Token: e060c682-40a5-48a9-a8a4-55b1ff6b41ca",
                "cache-control: no-cache"
            ),
        ));
        
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            if ($err) {
                $resp_data = $err;
                \Illuminate\Support\Facades\Log::error('UC Inventory Token genration error  ' . $err.' for cart '.$cart_id);
                self::saveUCLog('', $cart_id, '', $resp_data, '');
            } else {
                $resp_data = $response;
        }


        if (strpos($resp_data, 'GMT') !== false) {
            $mainres = explode('GMT', $resp_data);
            $resp_data = $mainres[1];
            $httpcode = $mainres[0] . 'GMT';
            self::saveUCLog('', $cart_id, '', $resp_data, $httpcode);
            $rc = json_decode($resp_data, true);
            //print_r($rc);exit();
            $token = $rc['access_token'];
             //\Illuminate\Support\Facades\Log::channel('slack')->error('test  ');
        }




        // print_r($resp_data);
            return $token;

       
    }
    
    public static function callInventoryUC($sku, $code, $cart_id,$token) {
        
        $res = false;
        $resp_data = '';
        $data = array(
            'itemTypeSKUs' => [$sku]
        );
        $data = json_encode($data);
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://tcns.unicommerce.com/services/rest/v1/inventory/inventorySnapshot/get",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HEADER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $token",
                    "Content-Type: application/json",
                    "cache-control: no-cache",
                    "facility: $code"
                ),
            ));
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            if ($err) {
                $resp_data = $err;
            } else {
                $res = true;
                $resp_data = $response;
            }
            $mainres = explode('GMT', $resp_data);
            $resp_data = $mainres[1];
            $httpcode = $mainres[0] . 'GMT';
            self::saveUCLog($code, $cart_id, $sku, $resp_data, $httpcode);
        } catch (\Exception $ex) {
            self::saveUCLog($code, $cart_id, $sku, $resp_data, $httpcode);
        }


        return [$res, $resp_data];
    }
 
    public static function saveUCLog($code, $cart_id, $sku, $res, $httpcode) {
        $file = public_path('uclogs').'/UC-Inventory-Check-Log___' . date("M-d-Y") . '.csv';
        if (!file_exists($file)) {
            $fp = fopen($file, 'a');  //Open file for append
            fputcsv($fp, array('Timestamp', 'Date', 'Faciltiy Code', 'Cart id', 'sku', 'quantity', 'Headers', 'UC Response')); //@Optimist
            fclose($fp);
            chmod($file, 0777);
        } 
            $fp = fopen($file, 'a');  //Open file for append
            date_default_timezone_set('Asia/Kolkata');
            $qty = '';
            $res_data = json_decode($res, true); //printt($res_data);
            if (isset($res_data['successful'])) {
            if ($res_data['successful'] == true) {
                $qty = $res_data['inventorySnapshots'][0]['inventory'];
            }
            }
            fputcsv($fp, array(time(), date('m/d/Y h:i:s a', time()), $code, $cart_id, $sku, $qty, $httpcode, $res)); //@Optimist
            fclose($fp);
       
    }
      public static function gumletCacheClear($imageUrl)
    {
        try {
            $gumlet_api_key = env('GUMLET_API_KEY');
            $req = array(
                'paths' => $imageUrl,
                
            ); 
            $encodedReq = json_encode($req);
            // dd($encodedReq);
            // $client = new \GuzzleHttp\Client();
            // $response = $client->request('POST', env('gumlatePurgeURL'), [
            //     'body' => $ImagesArray,
            //     'headers' => [
            //         'Accept' => 'application/json',
            //         'Authorization' => 'Bearer ' . $gumlet_api_key,
            //         'Content-Type' => 'application/json',
            //     ],
            // ]);
            // // dd($response->getBody());
            // return $response->getBody();

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => env('gumlatePurgeURL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $encodedReq,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer '.$gumlet_api_key
            ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
            // die;

        } catch (\Exception $e) {
            return ('Error ' . $e->getMessage() . '-' . $e->getFile() . '-' . $e->getLine());
        }
    }

}
