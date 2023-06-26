<?php
/**
* Implements hook_path_update().
*/

 $query2 = '//*[@class="productHeader__gallery__large__imgCont"]//img';
       $rows2 = $xpath2->query($query2);
       if ($rows2->length > 0) {
        foreach ($rows2 as $key2 => $imageItem) {

          $productImage=[];
          $productImage['alt']  = $imageItem->getAttribute('alt');
          $productImage['path']  = $imageItem->getAttribute('src');
          $product['productImage'] = $productImage;
        }
      }

$query2 = '//div[@class="productSection productSection--details col-sm-12  col-md-8 "]/h2[@class="productSection__title"]';
       $rows2 = $xpath2->query($query2);
       if ($rows2->length > 0) {
        foreach ($rows2 as $key2 => $pagTitle) {
     $product['title']  = utf8_decode($pagTitle->textContent);
        }


      }
      $query2 = '//ul[@class="productSection__list"]/li[@class="productSection__list__single"]/strong/span[@id="productDetailPrice"]';
       $rows2 = $xpath2->query($query2);

       if ($rows2->length > 0) {
        foreach ($rows2 as $key2 => $price) {
     $product['price'] = utf8_decode($price->textContent);
        }
      }



 $query2 = '//div[@class="row yaootaEmbedProduct"]';
       $rows2 = $xpath2->query($query2);
       if ($rows2->length > 0) {
        foreach ($rows2 as $key2 => $producID) {

     $product['producID'] = $producID->getAttribute('data-product-id');

        }

      }




      $query2 = '//table[@class="productSection__table table table-bordered productSection__table--specs"]/tbody/tr';
       $rows2 = $xpath2->query($query2);

       if ($rows2->length > 0) {
        foreach ($rows2 as $key2 => $specRow) {

     $product['spec'][str_replace(':','',utf8_decode($specRow->firstChild->textContent))] = utf8_decode($specRow->lastChild->textContent);

        }
      }
