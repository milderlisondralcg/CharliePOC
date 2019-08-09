<?php
header('Content-Type: application/json');
include("config/config.php");
include("config/database.php");
include("libraries/functions.php");
include("models/products.php");

// instantiate database and product object
$database = new Database();
$db_conn = $database->get_connection();

$products_obj = new Products($db_conn);
$products_list = $products_obj->get_products();

//$action = $_GET["action"];
$action = $_REQUEST["action"];
if( isset($_REQUEST["id"]) ){
	$id = trim($_REQUEST["id"]);
}

if( isset($_GET["output"]) ){
	$output = "explode";
}
$output = "json";

$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

switch( $action ){
	case "products":
		$products_list = $products_obj->get_products();
		if( $output == "explode"){
			print '<pre>'; print_r($products_list); print '</pre>';
		}else{
			print json_encode($products_list);
		}
		break;
	case "lmc":
		// LMC Subcategories
		$lmc_list_energy = $products_obj->get_lmc_nav_products('Energy'); // LMC
		$lmc_list_power = $products_obj->get_lmc_nav_products('Power'); // LMC
		$lmc_list_meter = $products_obj->get_lmc_nav_products('Power & Energy Meters'); // LMC
		$lmc_list_beam = $products_obj->get_lmc_nav_beam_products(); // LMC
	
		$energy_subcategory = build_nav_lmc($lmc_list_energy, "Energy Sensors"); // LMC
		$power_subcategory = build_nav_lmc($lmc_list_power, "Power Sensors"); // LMC
		$meters_subcategory = build_nav_lmc($lmc_list_meter, "Power & Energy Meters"); // LMC
		$beam_subcategory = build_nav_lmc($lmc_list_beam, "Beam Diagnostics"); // LMC
		
		// END LMC Subcategories

		$subcategories = array($energy_subcategory,$power_subcategory, $meters_subcategory,$beam_subcategory);
		
		$lmc_html_row1 = array("image"=>"/assets/site_images/laser_measurement_homepage.png",
					"title"=>"Laser Measurement Homepage",
					"text"=>"Coherent's Laser Measurement offering is more than just meters and sensors. Find the perfect product, explore our loyalty programs, and much more.",
					"link_text"=>"Go to Laser Measurement home",
					"link_url"=>"https://www.coherent.com/measurement-control/"
					);
					
		$lmc_html_row2 = array("image"=>"/assets/site_images/support_download_center.png",
					"title"=>"Support and Download Center",
					"text"=>"Your single source for technical information, product manuals, white papers, software downloads, Repair and RMA info, and FAQs.",
					"link_text"=>"Open the Support and Download Center",
					"link_url"=>"https://www.coherent.com/support"
					);					
		$lmc_html_row3 = array("image"=>"/assets/site_images/support_download_center.png",
					"title"=>"Support and Download Center",
					"text"=>"Your single source for technical information, product manuals, white papers, software downloads, Repair and RMA info, and FAQs.",
					"link_text"=>"Open the Support and Download Center",
					"link_url"=>"https://www.coherent.com/support"
					);					
					
		$lmc_list_total = array("name"=>"Laser Measurement","html_row"=>array($lmc_html_row1,$lmc_html_row2, $lmc_html_row3),"sub_categories"=>$subcategories);
		
		$filename = "product-nav-lmc-" . date("m-d-Y-h-i-g", time()) . ".json";
		$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
		$string_to_write = json_encode($lmc_list_total);
		fwrite($handle, $string_to_write);
		fclose($handle);
		
		break;
	case "get-nav":
	
		$lasers_list = $products_obj->get_products_by_category("lasers"); // Lasers
		$components_list = $products_obj->get_products_by_category("components"); // Components
		$tools_systems_list = $products_obj->get_products_by_category("tools_systems"); // Tools and Systems
		
		// LMC Subcategories
		$lmc_list_energy = $products_obj->get_lmc_nav_products('Energy'); // LMC
		$lmc_list_power = $products_obj->get_lmc_nav_products('Power'); // LMC
		$lmc_list_meter = $products_obj->get_lmc_nav_products('Power & Energy Meters'); // LMC
		$lmc_list_beam = $products_obj->get_lmc_nav_beam_products(); // LMC
	
		$energy_subcategory = build_nav_lmc($lmc_list_energy, "Energy Sensors"); // LMC
		$power_subcategory = build_nav_lmc($lmc_list_power, "Power Sensors"); // LMC
		$meters_subcategory = build_nav_lmc($lmc_list_meter, "Power & Energy Meters"); // LMC
		$beam_subcategory = build_nav_lmc($lmc_list_beam, "Beam Diagnostics"); // LMC
		
		// END LMC Subcategories
		
		
		//$lmc_list_total = array("name"=>"Laser Measurement","sub_categories"=>array(build_nav_lmc($lmc_list_energy),build_nav_lmc($lmc_list_power)));

		$subcategories = array($energy_subcategory,$power_subcategory, $meters_subcategory,$beam_subcategory);
		
		$lmc_html_row1 = array("image"=>"/assets/site_images/laser_measurement_homepage.png",
					"title"=>"Laser Measurement Homepage",
					"text"=>"Coherent's Laser Measurement offering is more than just meters and sensors. Find the perfect product, explore our loyalty programs, and much more.",
					"link_text"=>"Go to Laser Measurement home",
					"link_url"=>"https://www.coherent.com/measurement-control/"
					);
					
		$lmc_html_row2 = array("image"=>"/assets/site_images/support_download_center.png",
					"title"=>"Support and Download Center",
					"text"=>"Your single source for technical information, product manuals, white papers, software downloads, Repair and RMA info, and FAQs.",
					"link_text"=>"Open the Support and Download Center",
					"link_url"=>"https://www.coherent.com/support"
					);					
		$lmc_html_row3 = array("image"=>"/assets/site_images/support_download_center.png",
					"title"=>"Support and Download Center",
					"text"=>"Your single source for technical information, product manuals, white papers, software downloads, Repair and RMA info, and FAQs.",
					"link_text"=>"Open the Support and Download Center",
					"link_url"=>"https://www.coherent.com/support"
					);					
					
		$lmc_list_total = array("name"=>"Laser Measurement","html_row"=>array($lmc_html_row1,$lmc_html_row2, $lmc_html_row3),"sub_categories"=>$subcategories);

		/*
		$full_list = array("categories"=>array(
		build_nav($lasers_list, "lasers"),
		build_nav($components_list, "components"),
		build_nav($tools_systems_list, "tools_systems"),
		$lmc_list_total
		));
	*/
	
		$full_list = array("categories"=>array(
			build_nav($components_list, "lasers")));
			
		$filename = "json-files/product-nav-" . date("m-d-Y-h-i-g", time()) . ".json";
		$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
		$string_to_write = json_encode($full_list);
		fwrite($handle, $string_to_write);
		fclose($handle);		
		
		break;
	case "get-products-by-category":
		$category = "lasers";
		$cat = "lasers";
		if( isset($_REQUEST["category"]) ){
			$cat = trim($_REQUEST["category"]);
		}
		$products_list = $products_obj->get_products_by_category($cat);
		if( $cat != "lmc"){
			$response = build_nav($products_list, $cat);
		}else{
			$response = build_nav_lmc($products_list);
		}

		switch( $cat ){
			case "lasers":
				$filename = "product-nav-lasers.json";
				break;
			case "components":
				$filename = "product-nav-components.json";
				break;
			case "lmc":
				$filename = "product-nav-lmc.json";
				break;
			case "tools_systems":
				$filename = "product-nav-tools-systems.json";
				break;				
		}
		$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
		$string_to_write = json_encode($response);
		fwrite($handle, $string_to_write);
		fclose($handle);
		
		print json_encode($response);
		break;	
	case "get-product-detail":
		if( isset($id) ){
			$product_id = $id;
			// get details
			$product = $products_obj->get_product( $id );
			$temp_mod_date = date_create($product['Modified_Datetime']);
			$product['Modified_Datetime'] = date_format($temp_mod_date,"m/d/Y h:i A");

			if($product['Category_ID'] == '33' || $product['Category_ID'] == '23' ){ // lasers and components

				if( !array_key_exists('WAVELENGTH',$product)){
					$product['WAVELENGTH'] = "";
				}
				if( !array_key_exists('TECHNOLOGY',$product) ){
					$product['TECHNOLOGY'] = "";
				}
				if( !array_key_exists('POWER',$product)){
					$product['POWER'] = "";
				}	
				if( !array_key_exists('OPERATIONMODE',$product) ){
					$product['OPERATIONMODE'] = "";
				}
				if( !array_key_exists('PULSE_WIDTH',$product) ){
					$product['PULSE_WIDTH'] = "";
				}
				if( $product['Category_ID'] == '33' ){ // lasers specific field
					if( array_key_exists('BEAM_DIAMETER',$product) ){
						unset($product['BEAM_DIAMETER']);
					}						
				}				
				if( $product['Category_ID'] == '23' ){ // components specific field
					if( !array_key_exists('APPLICATIONS',$product) ){
						$product['APPLICATIONS'] = "";
					}						
				}			
			}
			if( $product['Category_ID'] == '53' ){ // tools and systems
				if( !array_key_exists('APPLICATIONS',$product)){
					$product['APPLICATIONS'] = "";
				}
				if( !array_key_exists('MATERIALS',$product)){
					$product['MATERIALS'] = "";
				}					
				if( !array_key_exists('MATERIAL_THICKNESS',$product)){
					$product['MATERIAL_THICKNESS'] = "";
				}	
				if( !array_key_exists('MOTION_TYPE',$product)){
					$product['MOTION_TYPE'] = "";
				}	
				if( !array_key_exists('MAX_WORK_AREA',$product)){
					$product['MAX_WORK_AREA'] = "";
				}	
				if( !array_key_exists('PRECISION',$product)){
					$product['PRECISION'] = "";
				}	
				if( array_key_exists('WAVELENGTH',$product) ){
					unset($product['WAVELENGTH']);
				}					
			}
			
			// Begin LMC
			if( $product['Category_ID'] == '43' ){
				
				// For Energy Sensors and Power Sensors
				if ( strpos($product['Name'], 'Energy') !== false || strpos($product['Name'], 'Power') !== false ) {
					if( !array_key_exists('ACTIVE_AREA_DIAMETER',$product)){
						$product['ACTIVE_AREA_DIAMETER'] = "";
					}	
					if( !array_key_exists('WAVELENGTH',$product)){
						$product['WAVELENGTH'] = "";
					}
					
					// Energy Sennsors only
					if ( strpos($product['Name'], 'Energy') !== false ){
						if( !array_key_exists('MIN_ENERGY',$product)){
							$product['MIN_ENERGY'] = "";
						}	
						if( !array_key_exists('MAX_ENERGY',$product)){
							$product['MAX_ENERGY'] = "";
						}
						if( !array_key_exists('REPETITION_RATE_MAX',$product)){
							$product['REPETITION_RATE_MAX'] = "";
						}	
						if( !array_key_exists('PULSE_WIDTH_MAX',$product)){
							$product['PULSE_WIDTH_MAX'] = "";
						}
					}
					// Power Sennsors only
					if ( strpos($product['Name'], 'Power') !== false ){
						if( !array_key_exists('POWER_MIN',$product)){
							$product['POWER_MIN'] = "";
						}	
						if( !array_key_exists('POWER_MAX',$product)){
							$product['POWER_MAX'] = "";
						}
						if( !array_key_exists('COOLING_METHOD',$product)){
							$product['COOLING_METHOD'] = "";
						}	

					}					
				}
				
				// For Power & Energy Meters
				if ( strpos($product['Name'], 'labmax') !== false || strpos($product['Name'], 'FieldMax') !== false || strpos($product['Name'], 'FieldMate') !== false || strpos($product['Name'], 'LaserCheck') !== false  ) {
					if( !array_key_exists('REPETITION_RATE_MAX',$product)){
						$product['REPETITION_RATE_MAX'] = "";
					}
					if( !array_key_exists('PC_INTERFACE',$product)){
						$product['PC_INTERFACE'] = "";
					}
					if( !array_key_exists('MEASUREMENT_TYPE',$product)){
						$product['MEASUREMENT_TYPE'] = "";
					}
					if( !array_key_exists('UNCERTAINTY',$product)){
						$product['UNCERTAINTY'] = "";
					}					
				}
				
				// For Beam Diagnostics
				if( $product['Family_ID'] == '13113' ){
					if( !array_key_exists('WAVELENGTH',$product)){
						$product['WAVELENGTH'] = "";
					}
					if( !array_key_exists('BEAM_DIAMETER',$product)){
						$product['BEAM_DIAMETER'] = "";
					}					
					if( !array_key_exists('ACTIVE_AREA',$product)){
						$product['ACTIVE_AREA'] = "";
					}	
					if( !array_key_exists('PIXEL_SIZE',$product)){
						$product['PIXEL_SIZE'] = "";
					}		
					if( !array_key_exists('PULSE_CW',$product)){
						$product['PULSE_CW'] = "";
					}					
				}
			
			} // End LMC

			
			print json_encode($product);
		}
		break;
		
	case "get-admin-products":
			$clean_list = "";
			
			$lasers_list = $products_obj->get_products_by_category("lasers"); // Lasers
			foreach($lasers_list as $data){
				$clean_list[] = array("ID"=>$data->ID,"Name"=>$data->Name);
			}
			
			//print_r($clean_list);
			//$components_list = $products_obj->get_products_by_category("components"); // Components
			//$tools_systems_list = $products_obj->get_products_by_category("tools_systems"); // Tools and Systems
			
			$filename = "json-files/admin-" . date("m-d-Y-h-i-g", time()) . ".json";
			$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
			$string_to_write = json_encode($clean_list);
			fwrite($handle, $string_to_write);
			fclose($handle);		
		break;
		
	case "get-admin-products-category":
			$clean_list = "";
			if( isset($_GET['category']) ){
				$product_category = trim($_GET['category']);
			}else{
				$product_category = "lasers";
			}
			
			$lasers_list = $products_obj->get_products_by_category($product_category, "no");
			foreach($lasers_list as $data){
				$clean_list[] = array("ID"=>$data->ID,"Name"=>$data->Name);
			}
			
			//print_r(array("Lasers"=>$clean_list));
			//$components_list = $products_obj->get_products_by_category("components"); // Components
			//$tools_systems_list = $products_obj->get_products_by_category("tools_systems"); // Tools and Systems
			
			print json_encode( $clean_list );
			/*
			$filename = "json-files/admin-" . date("m-d-Y-h-i-g", time()) . ".json";
			$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
			$string_to_write = json_encode($clean_list);
			fwrite($handle, $string_to_write);
			fclose($handle);	
*/			
		break;
	
	case "get-single-cat-nav":

		//debugging 08/20/2018
		switch($_REQUEST['category']){
			case "lasers":
			case "components":
			case "tools_systems":
				$products = $products_obj->get_products_by_category_sp($_REQUEST['category']);
				break;
			case "Energy":
			case "Power":
			case "Power & Energy Meters":
				$products = $products_obj->get_lmc_nav_products($_REQUEST['category']);
				break;
			default:
				$products = $products_obj->get_lmc_nav_products($_REQUEST['category']);
				break;
		}
		
		print_r($products);
		
		break;

		
	case "get-nav-sp":

	
		$lasers = $products_obj->get_products_by_category_sp('lasers');
		$components = $products_obj->get_products_by_category_sp('components');
		$tools_systems = $products_obj->get_products_by_category_sp('tools_systems');

		// LMC Subcategories
		$lmc_list_energy = $products_obj->get_lmc_nav_products('Energy'); // LMC		
		$lmc_list_power = $products_obj->get_lmc_nav_products('Power'); // LMC
		$lmc_list_meter = $products_obj->get_lmc_nav_products('Power & Energy Meters'); // LMC
		$lmc_list_beam = $products_obj->get_lmc_nav_beam_products(); // LMC
	
		$energy_subcategory = build_nav_lmc($lmc_list_energy, "Energy Sensors"); // LMC
		$power_subcategory = build_nav_lmc($lmc_list_power, "Power Sensors"); // LMC
		$meters_subcategory = build_nav_lmc($lmc_list_meter, "Power & Energy Meters"); // LMC
		$beam_subcategory = build_nav_lmc($lmc_list_beam, "Beam Diagnostics"); // LMC
		
		// END LMC Subcategories

		$subcategories = array($energy_subcategory,$power_subcategory, $meters_subcategory,$beam_subcategory);
		
		$lmc_html_row1 = array("image"=>"/assets/site_images/laser_measurement_homepage.png",
					"title"=>"Laser Measurement Homepage",
					"text"=>"Coherent's Laser Measurement offering is more than just meters and sensors. Find the perfect product, explore our loyalty programs, and much more.",
					"link_text"=>"Go to Laser Measurement home",
					"link_url"=>"https://www.coherent.com/measurement-control/"
					);
					
		$lmc_html_row2 = array("image"=>"/assets/site_images/support_download_center.png",
					"title"=>"Support and Download Center",
					"text"=>"Your single source for technical information, product manuals, white papers, software downloads, Repair and RMA info, and FAQs.",
					"link_text"=>"Open the Support and Download Center",
					"link_url"=>"https://www.coherent.com/support"
					);					
		$lmc_html_row3 = array("image"=>"/assets/site_images/support_download_center.png",
					"title"=>"Support and Download Center",
					"text"=>"Your single source for technical information, product manuals, white papers, software downloads, Repair and RMA info, and FAQs.",
					"link_text"=>"Open the Support and Download Center",
					"link_url"=>"https://www.coherent.com/support"
					);					
					
		$lmc_list_total = array("name"=>"Laser Measurement","html_row"=>array($lmc_html_row1,$lmc_html_row2, $lmc_html_row3),"sub_categories"=>$subcategories);

		/*
		$full_list = array("categories"=>array(
			build_nav_v2($lasers, "lasers"),
			build_nav_v2($components, "components"),
			build_nav_v2($tools_systems, "tools_systems"),
			$lmc_list_total
		));
		*/
		$full_list = array("categories"=>array(
			$lmc_list_total			
		));		
 
		$filename = "json-files/product-nav-" . date("m-d-Y-h-i-g", time()) . ".json";
		$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
		$string_to_write = json_encode($full_list);
		fwrite($handle, $string_to_write);
		fclose($handle); 
			
		
		break;
		
	case "time":
		print date("Y-m-d G:i:s", mktime());
		break;

}