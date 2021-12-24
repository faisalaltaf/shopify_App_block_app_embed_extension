<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckembadController extends Controller
{
    //
   public function index(Request $request){
$shop = auth()->user();


    $result = $shop->api()->rest('GET', '/admin/api/2021-10/themes.json');
    $result_1 = $shop->api()->rest('GET', '/admin/api/2021-10/webhooks.json');
    dd($result_1);
    // dd($result);
    $activeid = "";
    
    foreach ($result['body']->container['themes'] as $theme) {
      
      if ($theme['role'] === 'main') {
        $activeid = $theme['id'];
      }
    }
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
      // dd($embed);
// return $embed; 
// dd($embed);
// if($embed->current->blocks){
//   return 'hello';
//

// "blocks": {
//   "15944579539001155591": {
//     "type": "shopify:shopify://apps/themeblock/blocks/app-embed/2f28c9b0-a031-4e92-bf9d-5c5071973746",
//     "disabled": true,
//     "settings": {
//     }
//   }
//  };
// }
if(!isset($embed->current->blocks)){
  $embed->current->blocks = json_decode('{
  "15944579539001155591":
    
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

 return 'blocks';
}
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
//  return $value;
$embeded = $shop->api()->rest('PUT','/admin/api/2021-10/themes/'.$activeid.'/assets.json',$value);

 return 'id';
}
// dd($embed->current);
// if(!isset($embed->current->blocks->$id)){

// }
if(isset($embed->current->blocks->$id)){
  $embed->current->blocks->$id->disabled = true;
  return 'false';
}
// if($embed->current->blocks->$id){

// }
// dd($embed);
// dd($embed->current->blocks->$id->disabled = true);
// $embed->current->blocks->$id->disabled = true;
// $embed->current = ' "blocks": {
//   "15944579539001155591": {
//     "type": "shopify:\/\/apps\/themeblock\/blocks\/app-embed\/2f28c9b0-a031-4e92-bf9d-5c5071973746",
//     "disabled": true,
//     "settings": {
//     }
//   };';
// $embed['current']['blocks']['15944579539001155591']['disabled'] = false;
// return $embed;

    }
    
  }
  
}
