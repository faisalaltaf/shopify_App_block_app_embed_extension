<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class AfterAuthenticateJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $shopDomain;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shopDomain)
    {
        $this->shopDomain = $shopDomain['name'];
        // $this->shopDomain = Request('shop');

    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
           
        // $shop = auth()->user();
        $shop = User::where('name', $this->shopDomain)->firstOrFail();
        $shop_name=$shop->name;

    $result = $shop->api()->rest('GET', '/admin/api/2021-10/themes.json');
    // dd($result);
    $activeid = "";
    
    foreach ($result['body']->container['themes'] as $theme) {
      
      if ($theme['role'] === 'main') {
        $activeid = $theme['id'];
      }
    }
    //==asset api get== //
    $assets = $shop->api()->rest('GET', '/admin/api/2021-10/themes/'.$activeid.'/assets.json');
    if($shop->api()->rest(
      'GET',
      '/admin/api/2021-07/themes/'.$activeid.'/assets.json',
      ['asset[key]' => 'templates/index.json']
    )['body']){
      $embed = $shop->api()->rest(
        'GET',
        '/admin/api/2021-07/themes/'.$activeid.'/assets.json',
        ['asset[key]' => 'config/settings_data.json']
      )['body'];

      $embed = json_decode($embed['asset']['value']);
      $id = config('embed.extension_key');
      

          //==setting_data check blocks check  == //
if(!isset($embed->current->blocks)){
  $embed->current->blocks = json_decode('{
  "'.$id.'": {  
    {"type":"shopify:\/\/apps\/themeblock\/blocks\/app-embed\/2f28c9b0-a031-4e92-bf9d-5c5071973746","disabled":false,"settings":{}
  }}'); 
   $value =  
[
  "asset" => [
    "key" => "config/settings_data.json",
    "value" => json_encode($embed),
  ]];
//  return $value;
$embeded = $shop->api()->rest('PUT','/admin/api/2021-10/themes/'.$activeid.'/assets.json',$value);

}
   //==setting_data check block id check  == //
if(!isset($embed->current->blocks->$id)){
  $embed->current->blocks->$id = json_decode('
    {"type":"shopify:\/\/apps\/themeblock\/blocks\/app-embed\/2f28c9b0-a031-4e92-bf9d-5c5071973746","disabled":false,"settings":{}
  }'); 
   $value =  
[
  "asset" => [
    "key" => "config/settings_data.json",
    "value" => json_encode($embed),
  ]];
$embeded = $shop->api()->rest('PUT','/admin/api/2021-10/themes/'.$activeid.'/assets.json',$value);

}
 //==setting_data check block id disabled button value  == //
if(isset($embed->current->blocks->$id)){
  $embed->current->blocks->$id->disabled = false;
$embeded = $shop->api()->rest('PUT','/admin/api/2021-10/themes/'.$activeid.'/assets.json',$value);

}
    }
 
    }
}
