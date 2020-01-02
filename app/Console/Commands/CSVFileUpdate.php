<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductData;
use App\Models\CSVFileUpdateTime;
use Log;

class CSVFileUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csvfile_update:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to import csv file of products in database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
      //$file_n = public_path('/csv_file/product_details.csv');
       $file_n = "https://get.cpexp.de/pYD80muLq5m_1b0KTG5-1Tk1AkHNo1gLl_Z2acwsy5GJTs9kKdc8-_D1j0goHGft/cyberport-feedsmitmengen_emnamediavertriebde.csv";
      date_default_timezone_set('Europe/Berlin');
      $file_details = get_headers($file_n, 1);
      
      $updated_time =$file_details['last-modified'];
      
      //$updated_time = date ("F d Y H:i:s.", filemtime($file_n));
      $file_size = md5($updated_time);
      //$file_size = md5(filesize($file_n));
      $insertData = array('file_size'=>$file_size);

      $last_filesize = CSVFileUpdateTime::fetchData();
      // echo $file_size;
      // echo "<br>";
      // echo $last_filesize->file_size;
      // die();
      if($file_size != $last_filesize->file_size){
        CSVFileUpdateTime::insertData($insertData);
        $this->importProductCSV();
      }else{
        echo "same file/error";
      }
    }

    public function importProductCSV(){
      error_reporting(0);
      //$file_n = public_path('/csv_file/product_details.csv');
      
      $file_n = "https://get.cpexp.de/pYD80muLq5m_1b0KTG5-1Tk1AkHNo1gLl_Z2acwsy5GJTs9kKdc8-_D1j0goHGft/cyberport-feedsmitmengen_emnamediavertriebde.csv";

      $infoPath = pathinfo($file_n);
      if($infoPath['extension'] == 'csv'){
          $file = fopen($file_n, "r");
          date_default_timezone_set('Europe/Berlin');
          $file_details = get_headers($file_n, 1);
          $updated_time =$file_details['Last-Modified'];
          //  date_default_timezone_set('Europe/Berlin');
          // $updated_time = date ("F d Y H:i:s.", filemtime($file_n));
          $i = 0;
          $all_data = array();
          $last_filesize = CSVFileUpdateTime::fetchData();
          //$file_size = md5(filesize($file_n));
          $file_size = md5($updated_time);
          
              while ( ($filedata = fgetcsv($file, null, "|")) !==FALSE) {
                $num = count($filedata );
                for ($c=0; $c < $num; $c++) {
                  $all_data[$i][] = $filedata [$c];
                }
                $i++;
              }
              fclose($file);
              foreach($all_data as $importData){
                $insertData = array(
                       "article_number"=>$importData[0],
                       "article_name"=>$importData[1],
                       "article_description"=>$importData[2],
                       "article_price"=>$importData[3],
                       "article_manufacturer"=>$importData[6],
                       "article_productgroupkey"=>$importData[7],
                       "article_productgroup"=>$importData[8],
                       "article_ean"=>$importData[9],
                       "article_hbnr"=>$importData[10],
                       "article_shippingcosttext"=>$importData[11],
                       "article_amount"=>$importData[12],
                       "article_paymentinadvance"=>$importData[13],
                       "article_maxdeliveryamount"=>$importData[14],
                       "article_energyefficiencyclass"=>$importData[15]
                );
           
              ProductData::insertData($insertData);
              }
        
      }else{
        echo "Invalid file extension.";
      }

    }
}
