<?php

/**
 * @author Mahmoud Mokhtar
 *
 * Copyright (c) 2022. Electrony.com
 */
namespace Drupal\custom\Controller;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_price\Price;
use \Drupal\file\Entity\File;


use Drupal\Core\Controller\ControllerBase;

/**
 * Class function for run commands.
 */
class Utilites extends ControllerBase
{
    /**
     *  function for run commands.
     */
    public function runCommands()
    {
        /**
   * Scrapping Test Code
   */

   //set array of category
   $categoryLinks =[
     'mobiles-and-tablets'=>'/category/mobiles-and-tablets/',
     'computers-and-software'=>'/category/computers-and-software/',
     'electronics'=>'/category/electronics/',
     'home-goods'=>'/category/home/',
     'fashion-and-beauty'=>'/category/fashion-and-beauty/',
     'kids'=>'/category/kids/',
     'sports'=>'/category/sports/',
     'books-and-entertainment'=>'/category/books-and-entertainment/',
     'food-and-beverage'=>'/category/food-and-beverage/'
   ];


   //set Language arrays
   $langOptions=[
     'en'=>'/en-eg',
     'ar'=>'/ar-eg'
   ];
      // Get the html returned from the following url.
$main_url = "https://yaoota.com";
$nextLink = '';
foreach($langOptions as $lang)
{
  foreach($categoryLinks as $catKey => $categoryLink)
  {
    $pageCount=1;

    while($pageCount != null)
    {
      if($pageCount == 1)
      {
        $getLink = $main_url.$lang.$categoryLink.'?page='.$pageCount;
      }else{
        $getLink = $main_url.$nextLink;
      }

    $html = file_get_contents($getLink);
    $doc = new \DOMDocument();
    // Disable libxml errors.
    libxml_use_internal_errors(TRUE);
    // If any html is actually returned.
    if (!empty($html)) {
      $doc->loadHTML($html);

      // Remove errors for yucky html.
      libxml_clear_errors();
      $xpath = new \DOMXPath($doc);

//$body = $doc->getElementsByTagName('body')->item(0);
      // Get all anchors of items.
//$query = "//div[@class='listing']/div[last()]/node()[name() = 'h3' or name() = 'a']";
//$query = '/html/body//div[@class="search__container__result__products__single"]/div[last()]';
//$query = "/html/body//div[@class='search__container__result__products__single media hasProductRating']";
$query = "/html/body//div[@class='media-body']/h4/a";
      $rows = $xpath->query($query);
    //  dump($rows);
    //  exit;
      if ($rows->length > 0) {
        foreach ($rows as $key => $row) {
          $product = [];
          $product_link = $row->getAttribute('href');
          $product['link'] = $product_link;
           $html2 = file_get_contents($main_url.$product_link);
    $doc2 = new \DOMDocument();
    // Disable libxml errors.
   //libxml_use_internal_errors(TRUE);
    // If any html is actually returned.
    if (!empty($html2)) {
      $doc2->loadHTML($html2);
      // Remove errors for yucky html.
    //  libxml_clear_errors();
      $xpath2 = new \DOMXPath($doc2);



//fetch all Products data

  $query2 = '//product-header';
       $rows2 = $xpath2->query($query2);
       if ($rows2->length > 0) {
        foreach ($rows2 as $key2 => $productDetails) {
 $product=json_decode($productDetails->getAttribute('product'),true);
        }
      }

$query = \Drupal::entityQuery('commerce_product');
$query->condition('field_scrap_id', $product['id']);
$query->condition('status', 1);
$product_ids = $query->execute();
$fileRepository = \Drupal::service('file.repository');
$productImages=[];
foreach($product['images'] as $key=>$productImage){
$data = file_get_contents($productImage['url']);
$prodimage =$fileRepository->writeData($data, "public://products/".$product['title']."-".$key.".png");
if($productImage['is_primary'] && $key!=0 )
{
  $tempProductOrder = $productImages[0];
  $productImages[0] = [
    'target_id' => $prodimage->id(),
    'alt' => $product['title']."-".$key,
    'title' => $product['title']."-".$key
];
$productImages[$key] = $tempProductOrder;
}else{
$productImages[] = [
    'target_id' => $prodimage->id(),
    'alt' => $product['title']."-".$key,
    'title' => $product['title']."-".$key
];
}
}

if(empty($product_ids))
{
$variation = ProductVariation::create([
  'type' => 'basic',
  'sku' => $product['id'],
  'title'=> $product['title'],
  'price' => new Price($product['price'], 'EGP'),
  'field_image'=> $productImages
]);
$variation->save();

$newProduct = Product::create([
  'type' => 'basic_product',
  'title' => $product['title'],
  'body' => $product['description'],
  'field_scrap_id'=>$product['id'],
  'variations' => [$variation],
]);
$newProduct->save();
}
exit;

    }
          /*
          $path = $this->config->target_website . implode('/', array_map('urlencode', explode('/', $row->getAttribute("href"))));
          if ($this->config->limit == 0 || $key < $this->config->limit) {
            $this->pathDetails($path);
          }*/
        }
      }

    }

exit;
    $query = "//*[@aria-label='Next']";
      $rows = $xpath->query($query);
      if($rows->length > 0)
      {
         foreach ($rows as $key2 => $next) {
       $nextLink = $next->getAttribute('href');
      }
      }else{
     $pageCount = null;
    }

  }
    }

  }
}

}
