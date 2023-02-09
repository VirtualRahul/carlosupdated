<?php

namespace App\Console\Commands;

ini_set('max_execution_time', '0'); 

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class payureport extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payu:report';

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
        $headers = array(
            "Content-type" => "text/csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
       /* $handle = fopen(public_path('NewCartOrderList.csv'), 'w');
        $check = DB::connection('cart_mysql')->table('pim_orders')->select('pim_orders.order_increment_id','customer.phone','pim_orders.order_date')
                        ->join('customer', 'customer.id', '=', 'pim_orders.customer_id')
                        ->where('pim_orders.uc_sync', 1)->get();*/
         $handle = fopen(public_path('OLdCartOrderList.csv'), 'w');
        $check = DB::connection('o_mysql')->table('orders')->select('orders.order_id','customer.phone','orders.created_at','orders.magneto_response')
                        ->join('customer', 'customer.id', '=', 'orders.customer_id')
                        ->whereIn('orders.sync', [1,10])->get();
        foreach ($check as $ck) {
            if($ck->order_id!=null || $ck->order_id!=''){
                fputcsv($handle, array($ck->order_id, $ck->phone, date('y-m-d H:i:s', $ck->created_at)));
            }else{
                $rd= json_decode($ck->magneto_response,true);
                if(isset($rd['orderid'])){
                    fputcsv($handle, array($rd['orderid'], $ck->phone, date('y-m-d H:i:s', $ck->created_at)));
                }
                
            }
            
        }

        fclose($handle);
        exit();
    }

    public function handle1() {
        $headers = array(
        "Content-type" => "text/csv",        
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    );
        $handle = fopen(public_path('report.csv'), 'w');
        $ofile = fopen('php://output', 'w');
        $filename= public_path('Jun 19_1577258682.csv');
        $file = fopen($filename, 'r');
        
        while (($line = fgetcsv($file)) !== FALSE) {
            $order='';
            $check=DB::connection('o_mysql')->table('orders')->where('transid',$line[10])->first();
            if($check){
                $order= $check->order_id;
            }
            fputcsv($handle, array($order,$line[0],$line[1],$line[2],$line[3],$line[4],$line[5],$line[6],$line[7],$line[8],$line[9],$line[10],$line[11],$line[12],$line[13],$line[14],$line[15],$line[16],$line[17],$line[18],$line[19]));
        }
        fclose($file);
        fclose($handle);
        exit();
    }

}
