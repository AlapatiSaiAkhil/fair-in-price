<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Template Title -->
    <title>Fair In Price</title>

    <link rel="icon" href="images/favicon.ico" type="image/x-icon"/>

    <!-- Bootstrap 3.2.0 stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icon stylesheet -->
    <link href="css/font-awesome.min.css" rel="stylesheet">

    <!-- Owl Carousel stylesheet -->
    <link href="css/owl.carousel.css" rel="stylesheet">
    
    <!-- Pretty Photo stylesheet -->
    <link href="css/prettyPhoto.css" rel="stylesheet">

    <!-- Custom stylesheet -->
    <link href="style.css" rel="stylesheet">
    
    <link href="css/color/white.css" rel="stylesheet">


    <!-- Custom Responsive stylesheet -->
    <link href="css/responsive.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="background-color:#EEEEEE;">
<?php
error_reporting(0);
require_once('class.ebay.php');
include "next.php";
include "clusterdev.flipkart-api.php";
$ebay = new ebay('SaiAkhil-c2ac-4017-bac5-9b2af5b25f7d', 'EBAY-IN');
$sort_orders = $ebay->sortOrders();
?>

<!--<form action="ebay_finding_test.php" method="post">
	<input type="text" name="search" id="search">
	<select name="sort" id="sort">
	<?php
	foreach($sort_orders as $key => $sort_order){
	?>
		<option value="<?php echo $key; ?>"><?php echo $sort_order; ?></option>
	<?php	
	}
	?>
	</select>
	<input type="submit" value="Search">
</form>-->
<div class="container">
<div class="row">
  <div class="col-lg-5 col-md-5 col-sm-5" class="form-group">
    <strong><h1 style="font-family:Forte;font-size:60px;color:#080869;"> &nbsp;&nbsp;&nbsp;&nbsp;Product </h1></strong>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4" class="form-group">
    <img src="images\ebay.jpg">
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3" class="form-group">
    <img src="images\flipkart.jpg">
  </div>
</div>
</div>
<?php
if(isset($_POST['search']))
{
	$results = $ebay->findItemsAdvanced($_POST['search']);
	$item_count = $results['findItemsAdvancedResponse'][0]['searchResult'][0]['@count'];
	$search=$_POST['search'];
  $search=str_replace(" ", "+", $search);
  //Replace <affiliate-id> and <access-token> with the correct values
  $flipkart = new \clusterdev\Flipkart("saikrishn32", "fe2cac2932dd4d199f754f1003243fa3", "json");

  //To view category pages, API URL is passed as query string.
  $url ='https://affiliate-api.flipkart.net/affiliate/search/json?query='.$url.=$search.'&resultCount=10';
  $details = $flipkart->call_url($url);
  if(!$details)
  {
    echo 'Error: Could not retrieve products list.';
    exit();
  }

  $details = json_decode($details, TRUE);
 // $nextUrl = $details['nextUrl'];
  //$validTill = $details['validTill'];
  $products = $details['productInfoList'];
  $count = 0;
  $end = 1;
  if(count($products) > 0 || $item_count > 0)
  {

    $items = $results['findItemsAdvancedResponse'][0]['searchResult'][0]['item'];
    foreach($items as $i)
     {
    foreach ($products as $product)
     {

      //Hide out-of-stock items unless requested.
      $inStock = $product['productBaseInfo']['productAttributes']['inStock'];
      
      //Keep count.
      $count++;
      $title1 = $product['productBaseInfo']['productAttributes']['title'];
      if (preg_match("//i", $title1))
        {
          $productId = $product['productBaseInfo']['productIdentifier']['productId'];
          $title1 = $product['productBaseInfo']['productAttributes']['title'];
          $productDescription = $product['productBaseInfo']['productAttributes']['productDescription'];

          //We take the 200x200 image, there are other sizes too.
          $productImage = array_key_exists('200x200', $product['productBaseInfo']['productAttributes']['imageUrls'])?$product['productBaseInfo']['productAttributes']['imageUrls']['200x200']:'';
          $sellingPrice = $product['productBaseInfo']['productAttributes']['sellingPrice']['amount'];
          $productUrl = $product['productBaseInfo']['productAttributes']['productUrl'];
          $productBrand = $product['productBaseInfo']['productAttributes']['productBrand'];
          $color = $product['productBaseInfo']['productAttributes']['color'];
          $productUrl = $product['productBaseInfo']['productAttributes']['productUrl'];
          similar_text($title1,$i['title'][0],$per);
          //similar_text($sellingPrice,$i['sellingStatus'][0]['currentPrice'][0]['__value__'],$per);
          //echo $per;
              if($per>=75)
                {
?>
<div class="row">
  <div class="col-lg-5 col-md-5 col-sm-5" class="form-group">
      <div class="item_img">
        <center><img src="<?php echo $i['galleryURL'][0]; ?>" width="120" >
        </center>
      </div>
			<strong><div class="item_title" align="center">
				<?php echo $i['title'][0];?></br><?php echo $title1;?></a>
			</div></strong>		
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4" class="form-group">
  	<strong><div class="item_price" style="padding:20%;">
				<?php echo $i['sellingStatus'][0]['currentPrice'][0]['@currencyId']; ?>
				<?php echo $i['sellingStatus'][0]['currentPrice'][0]['__value__']; ?>
        <a target="_blank" href="<?php echo $i['viewItemURL'][0]; ?>"><input type="button" value="Buy Now"></a>
			</div></strong>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3" style="padding:6%;">
    <?php echo $product['productBaseInfo']['productAttributes']['sellingPrice']['amount'];?>
    <a target="_blank" href="<?php echo $product['productBaseInfo']['productAttributes']['productUrl'];?>"><img src="http://img6a.flixcart.com/www/prod/images/buy_btn_2-f780625c.png"></a>
  </div>
</div>
&nbsp;&nbsp;
<?php
          }
        }
      }
		}
	}		
}
?>

</body>
</html>
