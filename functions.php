<?php

global $dogId;

//update_option('siteurl','http://dallasnokill.org');
//update_option('home','http://dallasnokill.org');

function lorem_function() {
  return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nec nulla vitae lacus mattis volutpat eu at sapien. Nunc interdum congue libero, quis laoreet elit sagittis ut. Pellentesque lacus erat, dictum condimentum pharetra vel, malesuada volutpat risus. Nunc sit amet risus dolor. Etiam posuere tellus nisl. Integer lorem ligula, tempor eu laoreet ac, eleifend quis diam. Proin cursus, nibh eu vehicula varius, lacus elit eleifend elit, eget commodo ante felis at neque. Integer sit amet justo sed elit porta convallis a at metus. Suspendisse molestie turpis pulvinar nisl tincidunt quis fringilla enim lobortis. Curabitur placerat quam ac sem venenatis blandit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nullam sed ligula nisl. Nam ullamcorper elit id magna hendrerit sit amet dignissim elit sodales. Aenean accumsan consectetur rutrum.';
}

add_shortcode('lorem', 'lorem_function');


/*
	These are the filters at the top of the dog search page.
*/
function dog_filters_sc() {
	ob_start();
	?>
	<form action='' method='post' id='dog_filter_form' > 
					<div style="border:3px; border-style:solid; border-color:#5db879; text-align: center; margin-bottom: 20px; padding-top: 10px; padding-bottom: 10px;">
					<h3>Filter Results</h3>
					<strong>Size:</strong>
					<input type="radio" name="dogsize" value="all" <?php if (!isset($_POST['dogsize']) OR $_POST['dogsize'] == "all") echo "checked" ?>>All</option> 
					<input type="radio" name="dogsize" value="Small" <?php if (isset($_POST['dogsize']) AND $_POST['dogsize'] == "Small") echo "checked" ?>>Small</option> 
					<input type="radio" name="dogsize" value="Medium" <?php if (isset($_POST['dogsize']) AND $_POST['dogsize'] == "Medium") echo "checked" ?>>Medium</option> 
					<input type="radio" name="dogsize" value="Large" <?php if (isset($_POST['dogsize']) AND $_POST['dogsize'] == "Large") echo "checked" ?>>Large</option>
					<input type="radio" name="dogsize" value="X-Large" <?php if (isset($_POST['dogsize']) AND $_POST['dogsize'] == "X-Large") echo "checked" ?>>X-Large</option> 
					<br />
					<strong>Age:</strong>
					<input type="radio" name="dogage" value="all" <?php if (!isset($_POST['dogage']) OR $_POST['dogage'] == "all") echo "checked" ?>>All</option> 
					<input type="radio" name="dogage" value="Baby" <?php if (isset($_POST['dogage']) AND $_POST['dogage'] == "Baby") echo "checked" ?>>Baby</option> 
					<input type="radio" name="dogage" value="Young" <?php if (isset($_POST['dogage']) AND $_POST['dogage'] == "Young") echo "checked" ?>>Young</option> 
					<input type="radio" name="dogage" value="Adult" <?php if (isset($_POST['dogage']) AND $_POST['dogage'] == "Adult") echo "checked" ?>>Adult</option>
					<input type="radio" name="dogage" value="Senior" <?php if (isset($_POST['dogage']) AND $_POST['dogage'] == "Senior") echo "checked" ?>>Senior</option>
					<br />
					<strong>Gender:</strong>
					<input type="radio" name="doggender" value="all" <?php if (!isset($_POST['doggender']) OR $_POST['doggender'] == "all") echo "checked" ?>> All
					<input type="radio" name="doggender" value="Male" <?php if (isset($_POST['doggender']) AND $_POST['doggender'] == "Male") echo "checked" ?>> Male
					<input type="radio" name="doggender" value="Female" <?php if (isset($_POST['doggender']) AND $_POST['doggender'] == "Female") echo "checked" ?>> Female
					<br />
					<input type='submit' value = 'Filter' class="button"> 
					</div>
				</form>
	<?php
	return ob_get_clean();
}

add_shortcode('dog_filters', 'dog_filters_sc');

/*
	The list of dogs.
*/
function dog_list_sc() {
	ob_start();
	$filters = array(
		array(
			"fieldName" => "animalSpecies",
			"operation" => "equals",
			"criteria" => "dog",
		),
		array(
			"fieldName" => "animalStatus",
			"operation" => "equals",
			"criteria" => "Available",
		),
	);
	
	if(isset($_POST['dogsize'])) {
		$dogSize = $_POST['dogsize'];
		//print_r($dogSize);
		
		if(!($dogSize == "all")) {
			$sizeArray = array (
				array(
					"fieldName" => "animalGeneralSizePotential",
					"operation" => "equals",
					"criteria" => $dogSize,
				)
			);
			$filters = array_merge($filters, $sizeArray);
		}
	}
	
	if(isset($_POST['dogage'])) {
		$dogAge = $_POST['dogage'];
		//print_r($dogAge);
		
		if(!($dogAge == "all")) {
			$ageArray = array (
				array(
					"fieldName" => "animalGeneralAge",
					"operation" => "equals",
					"criteria" => $dogAge,
				)
			);
			$filters = array_merge($filters, $ageArray);
		}
	}
	
	if(isset($_POST['doggender'])) {
		$dogGender = $_POST['doggender'];
		//print_r($dogGender);
		
		if(!($dogGender == "all")) {
			$genderArray = array (
				array(
					"fieldName" => "animalSex",
					"operation" => "equals",
					"criteria" => $dogGender,
				)
			);
			$filters = array_merge($filters, $genderArray);
		}
	}
	
	//print_r($filters);

	$data = array(
		"apikey" => "QltdwQc9",
		"objectType" => "animals",
		"objectAction" => "publicSearch",
		"search" => array (
			"resultStart" => 0,
			"resultLimit" => 100,
			"resultSort" => "animalName",
			"resultOrder" => "asc",
			"calcFoundRows" => "Yes",    
			"filters" => $filters,
			"fields" => array("animalID","animalName","animalBreed","animalSex","animalThumbnailUrl","animalGeneralSizePotential","animalGeneralAge","animalPictures")
		),
	);
	
	$jsonData = json_encode($data);
	
	// create a new cURL resource
	$ch = curl_init();
	
	// set options, url, etc.
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	curl_setopt($ch, CURLOPT_URL, "https://api.rescuegroups.org/http/json");
	 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
	//curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$result = curl_exec($ch);
	
	if (curl_errno($ch)) {
		$results = curl_error($ch);
	} else {
		// close cURL resource, and free up system resources
		curl_close($ch);

		$results = $result;
	}
	
	$resultsArray = json_decode($results, true);
	
	$dogsList = $resultsArray['data'];
	
	$left = 1;
	$num = 0;
	
	foreach($dogsList as $dog) {
		$num = $num + 1;
		if($left) {
			echo "<div id=\"pet-search-left\">";
			$left = 0;
		} else {
			echo "<div id=\"pet-search-right\">";
			$left = 1;
		}

		echo "<a href=\"dog-profile?id=";
		echo $dog['animalID'];
		echo "\" style=\"text-decoration: underline; color: #006bb7;\">";
		//echo $dog['animalThumbnailUrl'];
		//echo "\" style=\"float: left; margin-right: 10px;\" /><div style=\"margin-left:110px;\"><h3>";
		if(count($dog['animalPictures']) > 0) {
			echo "<img src=\"";
			echo $dog['animalPictures'][0]['urlInsecureFullsize'];
			echo "\" style=\"float: left; margin-right: 10px; width:120px;\" /><div style=\"margin-left:130px;\"><h3>";
		}
		else {
			echo "<img src=\"http://dallaspetsalive.org/images/dlogo_120.jpg\" style=\"float: left; margin-right: 10px; width:120px;\" /><div style=\"margin-left:130px;\"><h3>";
		}
		//
		echo $dog['animalName'];
		echo "</h3></a>";
		echo $dog['animalGeneralAge'];
		echo " ";
		echo $dog['animalSex'];
		echo "<br />";
		echo $dog['animalGeneralSizePotential'];
		echo "<br />";
		echo $dog['animalBreed'];
		echo "</div></div>
		";
	}
	
	if($num == 0) {
		echo "<h2>No dogs found that match that criteria! Try a different filter.</h2>";
	}
	return ob_get_clean();
}

add_shortcode('dog_list', 'dog_list_sc');

/*
	The dog profile page.
*/
function dog_profile_sc () {
	global $dog;
	global $dogError;
	
	ob_start();
	
	if($dogError == 1) {
		echo "That dog can't be found!";
	} else {
		// display photos
		echo "<h1>" . $dog["animalName"] . "</h1>";
		if(count($dog["animalPictures"]) > 0) {
			// display main photo
			echo "<div id=\"dog-main-photo\"><img src=\"";
			echo $dog["animalPictures"][0]["urlInsecureFullsize"];
			echo "\" style=\"max-height:400px;\" id=\"dog-main-photo-img\"></div>";
			
			if(count($dog["animalPictures"]) > 1) {
				// display thumbs
				echo "<h2 style=\"margin-top:5px; text-align:center;\">Click a thumbnail below to see more photos!</h2>";
				echo "<div id=\"dog-photo-thumbs\">";
				foreach($dog["animalPictures"] as $dogPhoto) {
					echo "<img src=\"";
					echo $dogPhoto["urlInsecureThumbnail"];
					echo "\" onclick=\"document.getElementById('dog-main-photo-img').src='";
					echo $dogPhoto["urlInsecureFullsize"];
					echo "'\" id=\"dog-thumbnail\">";
				}
				echo "</div>";
			}
		}
		
		echo "<h1 style=\"margin-top:20px; font-weight:bold;\">Hi, my name is " . $dog["animalName"] . "!</h1></b><br />";
		echo "<h2>" . $dog["animalGeneralAge"] . " " . $dog["animalGeneralSizePotential"] . " " . $dog["animalSex"] . "<br/>";
		echo $dog["animalBreed"] . "</h2><br />";
		
		echo $dog["animalDescription"];
		
		//echo "<div style=\"text-align:center;margin-bottom:15px;\">";
		//if($dog["animalName"])
			//echo "<h3>" . $dog["animalName"] . "'s minimum adoption donation is " . $dog["animalAdoptionFee"] . ".</h3>";
			
		echo "</div> <!-- .et_pb_text --><div class=\"et_pb_promo et_pb_bg_layout_dark et_pb_text_align_center\" style=\"background-color: #006cb7;\">";
		echo "<div class=\"et_pb_promo_description\">";
		echo "<h3>Fill Out an Adoption Application for ";
		echo $dog["animalName"];
		echo " Online Today!</h3>";
				
		echo "</div>";
		echo "<a class=\"et_pb_promo_button\" href=\"adoption-application/?petId=" . $dog["animalName"];
		echo "\">Go to Adoption Application</a>";
		echo "</div><div class=\"et_pb_text et_pb_bg_layout_light et_pb_text_align_left\">";

		//echo "<a href=\"adoption-application/?petId=" . $dog["animalName"] . "\" style=\"font-size:1.4em;\"><b><u>Submit an Adoption Application Online for " 
		//	. $dog["animalName"] . "!</b></u></a>";
		//echo "</div>";
		
		
		// sponsor
		echo "<div id=\"dog-profile-left\"><div class=\"pet_desc_share\"><h3>Sponsor ";
		echo $dog["animalName"];
		echo "</h3></div>";
		
		echo "<div class=\"pet_sponsor\">";
		echo "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"cmd\" value=\"_donations\">";
		echo "<input type=\"hidden\" name=\"business\" value=\"WZM3XX65F5NGN\">";
		echo "<input type=\"hidden\" name=\"lc\" value=\"US\">";
		echo "<input type=\"hidden\" name=\"item_name\" value=\"";
		echo $dog["animalName"];
		echo "\">";
		echo "<input type=\"hidden\" name=\"no_note\" value=\"0\">";
		echo "<input type=\"hidden\" name=\"cn\" value=\"Add special instructions for DPA:\">";
		echo "<input type=\"hidden\" name=\"no_shipping\" value=\"0\">";
		echo "<input type=\"hidden\" name=\"currency_code\" value=\"USD\">";
		echo "<input type=\"image\" src=\"https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\">";
		echo "<img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\">";
		echo "</form>";
		echo "</div></div>";
		
		//share
		echo "<div id=\"dog-profile-right\"><div class=\"pet_desc_share\">";
		echo "<h3>Share ";
		echo $dog["animalName"];
		echo "</h3></div>";

		echo "<div class=\"feature_shares\">";
		echo "<a href=\"#\" 
			  onclick=\"
				window.open(
				  'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href), 
				  'facebook-share-dialog', 
				  'width=626,height=436'); 
				return false;\">
			  <b><u>Share on Facebook</b></u>
			</a><br /><br />";
		echo "<div class=\"fb-like\" id=\fb\" data-href=\"" . home_url() . $_SERVER["REQUEST_URI"] . "\" data-colorscheme=\"light\" data-layout=\"button_count\" data-action=\"like\" data-show-faces=\"false\" data-send=\"true\"></div>";
		echo "<div class=\"tweet\"><a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-via=\"DallasPetsAlive\" data-related=\"DallasPetsAlive\">Tweet</a></div>";
		echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);";
		echo "js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>";
		echo "</div></div>";
	}
	
	return ob_get_clean();
}

add_shortcode('dog_profile', 'dog_profile_sc');

function get_dog_name_sc () {
	global $dog;
	
	return $dog["animalName"];
}

add_shortcode('get_dog_name', 'get_dog_name_sc');

/*************
	begin cat changes
	**************/
	
/*
	These are the filters at the top of the dog search page.
*/
function cat_filters_sc() {
	ob_start();
	?>
	<form action='' method='post' id='cat_filter_form' > 
					<div style="border:3px; border-style:solid; border-color:#5db879; text-align: center; margin-bottom: 20px; padding-top: 10px; padding-bottom: 10px;">
					<h3>Filter Results</h3>
					<strong>Age:</strong>
					<input type="radio" name="catage" value="all" <?php if (!isset($_POST['catage']) OR $_POST['catage'] == "all") echo "checked" ?>>All</option> 
					<input type="radio" name="catage" value="Baby" <?php if (isset($_POST['catage']) AND $_POST['catage'] == "Baby") echo "checked" ?>>Baby</option> 
					<input type="radio" name="catage" value="Young" <?php if (isset($_POST['catage']) AND $_POST['catage'] == "Young") echo "checked" ?>>Young</option> 
					<input type="radio" name="catage" value="Adult" <?php if (isset($_POST['catage']) AND $_POST['catage'] == "Adult") echo "checked" ?>>Adult</option>
					<input type="radio" name="catage" value="Senior" <?php if (isset($_POST['catage']) AND $_POST['catage'] == "Senior") echo "checked" ?>>Senior</option>
					<br />
					<strong>Coat Length:</strong>
					<input type="radio" name="catcoat" value="all" <?php if (!isset($_POST['catcoat']) OR $_POST['catcoat'] == "all") echo "checked" ?>>All</option> 
					<input type="radio" name="catcoat" value="Short" <?php if (isset($_POST['catcoat']) AND $_POST['catcoat'] == "Short") echo "checked" ?>>Short</option> 
					<input type="radio" name="catcoat" value="Medium" <?php if (isset($_POST['catcoat']) AND $_POST['catcoat'] == "Medium") echo "checked" ?>>Medium</option> 
					<input type="radio" name="catcoat" value="Long" <?php if (isset($_POST['catcoat']) AND $_POST['catcoat'] == "Long") echo "checked" ?>>Long</option>
					<br />
					<strong>Gender:</strong>
					<input type="radio" name="catgender" value="all" <?php if (!isset($_POST['catgender']) OR $_POST['catgender'] == "all") echo "checked" ?>> All
					<input type="radio" name="catgender" value="Male" <?php if (isset($_POST['catgender']) AND $_POST['catgender'] == "Male") echo "checked" ?>> Male
					<input type="radio" name="catgender" value="Female" <?php if (isset($_POST['catgender']) AND $_POST['catgender'] == "Female") echo "checked" ?>> Female
					<br />
					<input type='submit' value = 'Filter' class="button"> 
					</div>
				</form>
	<?php
	return ob_get_clean();
}

add_shortcode('cat_filters', 'cat_filters_sc');

/*
	The list of cats.
*/
function cat_list_sc() {
	ob_start();
	$filters = array(
		array(
			"fieldName" => "animalSpecies",
			"operation" => "equals",
			"criteria" => "cat",
		),
		array(
			"fieldName" => "animalStatus",
			"operation" => "equals",
			"criteria" => "Available",
		),
	);
	
	if(isset($_POST['catcoat'])) {
		$catCoat = $_POST['catcoat'];
		print_r($catCoat);
		
		if(!($catCoat == "all")) {
			$coatArray = array (
				array(
					"fieldName" => "animalCoatLength",
					"operation" => "equals",
					"criteria" => $catCoat,
				)
			);
			$filters = array_merge($filters, $coatArray);
		}
	}
	
	if(isset($_POST['catage'])) {
		$catAge = $_POST['catage'];
		print_r($catAge);
		
		if(!($catAge == "all")) {
			$ageArray = array (
				array(
					"fieldName" => "animalGeneralAge",
					"operation" => "equals",
					"criteria" => $catAge,
				)
			);
			$filters = array_merge($filters, $ageArray);
		}
	}
	
	if(isset($_POST['catgender'])) {
		$catGender = $_POST['catgender'];
		print_r($catGender);
		
		if(!($catGender == "all")) {
			$genderArray = array (
				array(
					"fieldName" => "animalSex",
					"operation" => "equals",
					"criteria" => $catGender,
				)
			);
			$filters = array_merge($filters, $genderArray);
		}
	}

	$data = array(
		"apikey" => "QltdwQc9",
		"objectType" => "animals",
		"objectAction" => "publicSearch",
		"search" => array (
			"resultStart" => 0,
			"resultLimit" => 100,
			"resultSort" => "animalName",
			"resultOrder" => "asc",
			"calcFoundRows" => "Yes",    
			"filters" => $filters,
			"fields" => array("animalID","animalName","animalBreed","animalSex","animalThumbnailUrl","animalCoatLength","animalGeneralAge","animalPictures")
		),
	);
	
	$jsonData = json_encode($data);
	
	// create a new cURL resource
	$ch = curl_init();
	
	// set options, url, etc.
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	curl_setopt($ch, CURLOPT_URL, "https://api.rescuegroups.org/http/json");
	 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
	//curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$result = curl_exec($ch);
	
	if (curl_errno($ch)) {
		$results = curl_error($ch);
	} else {
		// close cURL resource, and free up system resources
		curl_close($ch);

		$results = $result;
	}
	
	$resultsArray = json_decode($results, true);
	
	$catsList = $resultsArray['data'];
	
	$left = 1;
	$num = 0;
	
	foreach($catsList as $cat) {
		$num = $num + 1;
		if($left) {
			echo "<div id=\"pet-search-left\">";
			$left = 0;
		} else {
			echo "<div id=\"pet-search-right\">";
			$left = 1;
		}

		echo "<a href=\"cat-profile?id=";
		echo $cat['animalID'];
		echo "\" style=\"text-decoration: underline; color: #006bb7;\">";
		//echo $cat['animalThumbnailUrl'];
		//echo "\" style=\"float: left; margin-right: 10px;\" /><div style=\"margin-left:110px;\"><h3>";
		if(count($cat['animalPictures']) > 0) {
			echo "<img src=\"";
			echo $cat['animalPictures'][0]['urlInsecureFullsize'];
			echo "\" style=\"float: left; margin-right: 10px; width:120px;\" /><div style=\"margin-left:130px;\"><h3>";
		}
		else {
			echo "<img src=\"http://dallaspetsalive.org/images/dlogo_120.jpg\" style=\"float: left; margin-right: 10px; width:120px;\" /><div style=\"margin-left:130px;\"><h3>";
		}
		//
		echo $cat['animalName'];
		echo "</h3></a>";
		echo $cat['animalGeneralAge'];
		echo " ";
		echo $cat['animalSex'];
		echo "<br />";
		echo $cat['animalBreed'];
		echo "</div></div>
		";
	}
	
	if($num == 0) {
		echo "<h2>No cats found that match that criteria! Try a different filter.</h2>";
	}
	return ob_get_clean();
}

add_shortcode('cat_list', 'cat_list_sc');

/*
	The cat profile page.
*/
function cat_profile_sc () {
	global $cat;
	global $catError;
	
	ob_start();
	
	if($catError == 1) {
		echo "That cat can't be found!";
	} else {
		// display photos
		echo "<h1>" . $cat["animalName"] . "</h1>";
		if(count($cat["animalPictures"]) > 0) {
			// display main photo
			echo "<div id=\"cat-main-photo\"><img src=\"";
			echo $cat["animalPictures"][0]["urlInsecureFullsize"];
			echo "\" style=\"max-height:400px;\" id=\"cat-main-photo-img\"></div>";
			
			if(count($cat["animalPictures"]) > 1) {
				// display thumbs
				echo "<h2 style=\"margin-top:5px; text-align:center;\">Click a thumbnail below to see more photos!</h2>";
				echo "<div id=\"cat-photo-thumbs\">";
				foreach($cat["animalPictures"] as $catPhoto) {
					echo "<img src=\"";
					echo $catPhoto["urlInsecureThumbnail"];
					echo "\" onclick=\"document.getElementById('cat-main-photo-img').src='";
					echo $catPhoto["urlInsecureFullsize"];
					echo "'\" id=\"cat-thumbnail\">";
				}
				echo "</div>";
			}
		}
		
		echo "<h1 style=\"margin-top:20px; font-weight:bold;\">Hi, my name is " . $cat["animalName"] . "!</h1></b><br />";
		echo "<h2>" . $cat["animalGeneralAge"] . " " . $cat["animalSex"] . "<br/>";
		echo $cat["animalBreed"] . "</h2><br />";
		
		echo $cat["animalDescription"];
		
		//echo "<div style=\"text-align:center;margin-bottom:15px;\">";
		//if($dog["animalName"])
			//echo "<h3>" . $dog["animalName"] . "'s minimum adoption donation is " . $dog["animalAdoptionFee"] . ".</h3>";
			
		echo "</div> <!-- .et_pb_text --><div class=\"et_pb_promo et_pb_bg_layout_dark et_pb_text_align_center\" style=\"background-color: #006cb7;\">";
		echo "<div class=\"et_pb_promo_description\">";
		echo "<h3>Fill Out an Adoption Application for ";
		echo $dog["animalName"];
		echo " Online Today!</h3>";
				
		echo "</div>";
		echo "<a class=\"et_pb_promo_button\" href=\"adoption-application/?petId=" . $dog["animalName"];
		echo "\">Go to Adoption Application</a>";
		echo "</div><div class=\"et_pb_text et_pb_bg_layout_light et_pb_text_align_left\">";

		//echo "<a href=\"adoption-application/?petId=" . $dog["animalName"] . "\" style=\"font-size:1.4em;\"><b><u>Submit an Adoption Application Online for " 
		//	. $dog["animalName"] . "!</b></u></a>";
		//echo "</div>";
		
		
		// sponsor
		echo "<div id=\"cat-profile-left\"><div class=\"pet_desc_share\"><h3>Sponsor ";
		echo $dog["animalName"];
		echo "</h3></div>";
		
		echo "<div class=\"pet_sponsor\">";
		echo "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"cmd\" value=\"_donations\">";
		echo "<input type=\"hidden\" name=\"business\" value=\"WZM3XX65F5NGN\">";
		echo "<input type=\"hidden\" name=\"lc\" value=\"US\">";
		echo "<input type=\"hidden\" name=\"item_name\" value=\"";
		echo $dog["animalName"];
		echo "\">";
		echo "<input type=\"hidden\" name=\"no_note\" value=\"0\">";
		echo "<input type=\"hidden\" name=\"cn\" value=\"Add special instructions for DPA:\">";
		echo "<input type=\"hidden\" name=\"no_shipping\" value=\"0\">";
		echo "<input type=\"hidden\" name=\"currency_code\" value=\"USD\">";
		echo "<input type=\"image\" src=\"https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\">";
		echo "<img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\">";
		echo "</form>";
		echo "</div></div>";
		
		//share
		echo "<div id=\"cat-profile-right\"><div class=\"pet_desc_share\">";
		echo "<h3>Share ";
		echo $cat["animalName"];
		echo "</h3></div>";

		echo "<div class=\"feature_shares\">";
		echo "<a href=\"#\" 
			  onclick=\"
				window.open(
				  'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href), 
				  'facebook-share-dialog', 
				  'width=626,height=436'); 
				return false;\">
			  <b><u>Share on Facebook</b></u>
			</a><br /><br />";
		echo "<div class=\"fb-like\" id=\fb\" data-href=\"" . home_url() . $_SERVER["REQUEST_URI"] . "\" data-colorscheme=\"light\" data-layout=\"button_count\" data-action=\"like\" data-show-faces=\"false\" data-send=\"true\"></div>";
		echo "<div class=\"tweet\"><a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-via=\"DallasPetsAlive\" data-related=\"DallasPetsAlive\">Tweet</a></div>";
		echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);";
		echo "js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>";
		echo "</div></div>";
	}
	
	return ob_get_clean();
}

add_shortcode('cat_profile', 'cat_profile_sc');

function get_cat_name_sc () {
	global $cat;
	
	return $cat["animalName"];
}

add_shortcode('get_cat_name', 'get_cat_name_sc');

function dpa_title_cat() {
	global $catId;

	if(isset($_GET['id'])) {
		$catId = $_GET['id'];
		
		$filters = array(
			array(
				"fieldName" => "animalID",
				"operation" => "equals",
				"criteria" => $catId,
			),
		);	
	}
	
	$data = array(
		"apikey" => "QltdwQc9",
		"objectType" => "animals",
		"objectAction" => "publicSearch",
		"search" => array (
			"resultStart" => 0,
			"resultLimit" => 1,
			"resultSort" => "animalName",
			"resultOrder" => "asc",
			"calcFoundRows" => "Yes",    
			"filters" => $filters,
			"fields" => array("animalID","animalName","animalBreed","animalSex","animalThumbnailUrl","animalCoatLength","animalGeneralAge","animalPictures","animalDescription","animalAdoptionFee")
		),
	);
	
	$jsonData = json_encode($data);
	
	// create a new cURL resource
	$ch = curl_init();

	// set options, url, etc.
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	curl_setopt($ch, CURLOPT_URL, "https://api.rescuegroups.org/http/json");
	 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
	//curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec($ch);
	
	if (curl_errno($ch)) {
		$results = curl_error($ch);
	} else {
		// close cURL resource, and free up system resources
		curl_close($ch);

		$results = $result;
	}
	
	$resultsArray = json_decode($results, true);

	$catData = $resultsArray['data'];

	$catCount = 0;
	global $catError;
	$catError = 0;
	
	global $cat;
	
	foreach($catData as $cat) {
		$catCount++;
		
		$catName = $cat["animalName"];
		if(count($cat["animalPictures"]) > 0) {
			$catThumbnail = $cat["animalPictures"][0]["urlInsecureFullsize"];
		}
		else {
			$catThumbnail = "http://dallaspetsalive.org/images/site_graphics/logo.gif";
		}
	} 
	
	if($catCount != 1) {
		$catError = 1;
	} else {
		echo '<title>' . $catName . ' | Dallas Pets Alive!' . '</title>';
		
		echo '<meta property="fb:admins" content="ktbird"/>';
        echo '<meta property="og:title" content="Adopt ' . $catName . ' | Dallas Pets Alive!"/>';
        echo '<meta property="og:type" content="website"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '?id=' . $catId . '"/>';
        echo '<meta property="og:site_name" content="Dallas Pets Alive"/>';
        echo '<meta property="og:image" content="' . $catThumbnail . '"/>';
		echo '<meta property="og:description" content="' . $catName . ' is available for adoption from Dallas Pets Alive! Click to find out more.' . '"/>';
	}
}

/***********
	end cat changes
	**********/

function og_dpa () {
	echo '<meta property="fb:admins" content="ktbird"/>';
	echo '<meta property="og:title" content="' . get_the_title() . '"/>';
	echo '<meta property="og:type" content="website"/>';
	echo '<meta property="og:url" content="' . get_permalink() . '"/>';
	echo '<meta property="og:site_name" content="Dallas Pets Alive"/>';
	//echo '<meta property="og:image" content="' . $dogThumbnail . '"/>';
}

function dpa_title() {
	global $dogId;

	if(isset($_GET['id'])) {
		$dogId = $_GET['id'];
		
		$filters = array(
			array(
				"fieldName" => "animalID",
				"operation" => "equals",
				"criteria" => $dogId,
			),
		);	
	}
	
	$data = array(
		"apikey" => "QltdwQc9",
		"objectType" => "animals",
		"objectAction" => "publicSearch",
		"search" => array (
			"resultStart" => 0,
			"resultLimit" => 1,
			"resultSort" => "animalName",
			"resultOrder" => "asc",
			"calcFoundRows" => "Yes",    
			"filters" => $filters,
			"fields" => array("animalID","animalName","animalBreed","animalSex","animalThumbnailUrl","animalGeneralSizePotential","animalGeneralAge","animalPictures","animalDescription","animalAdoptionFee")
		),
	);
	
	$jsonData = json_encode($data);
	
	// create a new cURL resource
	$ch = curl_init();

	// set options, url, etc.
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	curl_setopt($ch, CURLOPT_URL, "https://api.rescuegroups.org/http/json");
	 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
	//curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec($ch);
	
	if (curl_errno($ch)) {
		$results = curl_error($ch);
	} else {
		// close cURL resource, and free up system resources
		curl_close($ch);

		$results = $result;
	}
	
	$resultsArray = json_decode($results, true);

	$dogData = $resultsArray['data'];

	$dogCount = 0;
	global $dogError;
	$dogError = 0;
	
	global $dog;
	
	foreach($dogData as $dog) {
		$dogCount++;
		
		$dogName = $dog["animalName"];
		if(count($dog["animalPictures"]) > 0) {
			$dogThumbnail = $dog["animalPictures"][0]["urlInsecureFullsize"];
		}
		else {
			$dogThumbnail = "http://dallaspetsalive.org/images/site_graphics/logo.gif";
		}
	} 
	
	if($dogCount != 1) {
		$dogError = 1;
	} else {
		echo '<title>' . $dogName . ' | Dallas Pets Alive!' . '</title>';
		
		echo '<meta property="fb:admins" content="ktbird"/>';
        echo '<meta property="og:title" content="Adopt ' . $dogName . ' | Dallas Pets Alive!"/>';
        echo '<meta property="og:type" content="website"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '?id=' . $dogId . '"/>';
        echo '<meta property="og:site_name" content="Dallas Pets Alive"/>';
        echo '<meta property="og:image" content="' . $dogThumbnail . '"/>';
		echo '<meta property="og:description" content="' . $dogName . ' is available for adoption from Dallas Pets Alive! Click to find out more.' . '"/>';
	}
}

/*
	The cheat sheet... not really used anymore
*/
function cheat_sheet_sc() {
	ob_start();
	
	$filters = array(
		array(
			"fieldName" => "animalSpecies",
			"operation" => "equals",
			"criteria" => "dog",
		),
		array(
			"fieldName" => "animalStatus",
			"operation" => "equals",
			"criteria" => "Available",
		),
	);

	$data = array(
		"apikey" => "QltdwQc9",
		"objectType" => "animals",
		"objectAction" => "publicSearch",
		"search" => array (
			"resultStart" => 0,
			"resultLimit" => 100,
			"resultSort" => "animalName",
			"resultOrder" => "asc",
			"calcFoundRows" => "Yes",    
			"filters" => $filters,
			"fields" => array("animalID","animalName","animalBreed","animalSex","animalThumbnailUrl","animalGeneralSizePotential","animalGeneralAge","animalAdoptionFee","animalOKWithCats","animalOKWithDogs","animalOKWithKids","animalRescueID","animalPictures")
		),
	);
	
	$jsonData = json_encode($data);
	
	// create a new cURL resource
	$ch = curl_init();
	
	// set options, url, etc.
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	curl_setopt($ch, CURLOPT_URL, "https://api.rescuegroups.org/http/json");
	 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
	//curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$result = curl_exec($ch);
	
	if (curl_errno($ch)) {
		$results = curl_error($ch);
	} else {
		// close cURL resource, and free up system resources
		curl_close($ch);

		$results = $result;
	}
	
	//echo $results;
	
	$resultsArray = json_decode($results, true);
	
	$dogsList = $resultsArray['data'];
	
	$left = 1;
	$num = 0;
	
	foreach($dogsList as $dog) {
		$num = $num + 1;
		
			echo "<div id=\"pet-search-left\">";
			

		echo "<a href=\"dog-profile?id=";
		echo $dog['animalID'];
		echo "\" style=\"text-decoration: underline; color: #006bb7;\">";
		//echo $dog['animalThumbnailUrl'];
		//echo "\" style=\"float: left; margin-right: 10px;\" /><div style=\"margin-left:110px;\"><h3>";
		if(count($dog['animalPictures']) > 0) {
			echo "<img src=\"";
			echo $dog['animalPictures'][0]["urlInsecureThumbnail"];
			echo "\" style=\"float: left; margin-right: 10px; width:120px;\" /><div style=\"margin-left:130px;\"><h3>";
		}
		else {
			echo "<img src=\"http://dallaspetsalive.org/images/dlogo_120.jpg\" style=\"float: left; margin-right: 10px; width:120px;\" /><div style=\"margin-left:130px;\"><h3>";
		}
		//
		echo $dog['animalName'];
		echo "</h3></a>";
		echo $dog['animalGeneralSizePotential'];
		echo " ";
		echo $dog['animalGeneralAge'];
		echo " ";
		echo $dog['animalSex'];
		echo "<br />";
		echo $dog['animalBreed'];
		echo "<br />";
		echo "Adoption fee: ";
		echo $dog['animalAdoptionFee'];
		echo "<br />";
		echo "OK w/ cats: ";
		echo $dog['animalOKWithCats'];
		echo "<br />";
		echo "OK w/ dogs: ";
		echo $dog['animalOKWithDogs'];
		echo "<br />";
		echo "OK w/ kids: ";
		echo $dog['animalOKWithKids'];
		echo "<br />";
		echo "ID: ";
		echo $dog['animalRescueID'];
		echo "</div></div>";
	}
	
	
	
	if($num == 0) {
		echo "<h2>No dogs found that match that criteria! Try a different filter.</h2>";
	}
	return ob_get_clean();
}

add_shortcode('cheat_sheet', 'cheat_sheet_sc');

/*
function back_in_black_sc() {
	ob_start();
	$filters = array(
		array(
			"fieldName" => "animalSpecies",
			"operation" => "equals",
			"criteria" => "dog",
		),
		array(
			"fieldName" => "animalStatus",
			"operation" => "equals",
			"criteria" => "Available",
		),
		array(
			"fieldName" => "animalColorDetails",
			"operation" => "contains",
			"criteria" => "Black",
		),
	);
	
	//print_r($filters);

	$data = array(
		"apikey" => "QltdwQc9",
		"objectType" => "animals",
		"objectAction" => "publicSearch",
		"search" => array (
			"resultStart" => 0,
			"resultLimit" => 40,
			"resultSort" => "animalName",
			"resultOrder" => "asc",
			"calcFoundRows" => "Yes",    
			"filters" => $filters,
			"fields" => array("animalID","animalName","animalBreed","animalSex","animalThumbnailUrl","animalGeneralSizePotential","animalGeneralAge","animalPictures","animalColorDetails")
		),
	);
	
	$jsonData = json_encode($data);
	
	// create a new cURL resource
	$ch = curl_init();
	
	// set options, url, etc.
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	curl_setopt($ch, CURLOPT_URL, "https://api.rescuegroups.org/http/json");
	 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 
	//curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$result = curl_exec($ch);
	
	if (curl_errno($ch)) {
		$results = curl_error($ch);
	} else {
		// close cURL resource, and free up system resources
		curl_close($ch);

		$results = $result;
	}
	
	//print_r($results);
	
	$resultsArray = json_decode($results, true);
	
	$dogsList = $resultsArray['data'];
	
	$left = 1;
	$num = 0;
	
	foreach($dogsList as $dog) {
		$num = $num + 1;
		if($left) {
			echo "<div id=\"pet-search-left\">";
			$left = 0;
		} else {
			echo "<div id=\"pet-search-right\">";
			$left = 1;
		}

		echo "<a href=\"http://dallaspetsalive.org/adopt/adoptable-dogs/dog-profile?id=";
		echo $dog['animalID'];
		echo "\" style=\"text-decoration: underline; color: #006bb7;\">";
		//echo $dog['animalThumbnailUrl'];
		//echo "\" style=\"float: left; margin-right: 10px;\" /><div style=\"margin-left:110px;\"><h3>";
		if(count($dog['animalPictures']) > 0) {
			echo "<img src=\"";
			echo $dog['animalPictures'][0]['urlInsecureFullsize'];
			echo "\" style=\"float: left; margin-right: 10px; width:120px;\" /><div style=\"margin-left:130px;\"><h3>";
		}
		else {
			echo "<img src=\"http://dallaspetsalive.org/images/dlogo_120.jpg\" style=\"float: left; margin-right: 10px; width:120px;\" /><div style=\"margin-left:130px;\"><h3>";
		}
		//
		echo $dog['animalName'];
		echo "</h3></a>";
		echo $dog['animalGeneralAge'];
		echo " ";
		echo $dog['animalSex'];
		echo "<br />";
		echo $dog['animalGeneralSizePotential'];
		echo "<br />";
		echo $dog['animalBreed'];
		echo "</div></div>
		";
	}
	
	if($num == 0) {
		echo "<h2>No dogs found that match that criteria! Try a different filter.</h2>";
	}
	return ob_get_clean();
}

add_shortcode('back_in_black', 'back_in_black_sc');
*/

?>