<?php

class ProductAdmin{
 
    // database connection and table name
    private $conn;
	private $general = "pd_products_general";
	private $products_categories = "pd_products_categories";
	private $applications = "pd_applications";
	private $attributes = "pd_products_attributes";
	//private $attributes = "view_products_attributes";
	private $products_families = "pd_products_families";
	private $admin_actions = "pd_log_admin_action";
	
	private $view_products = "view_products_by_categories";
	private $view_products_applications = "view_applications_by_product";
	private $view_products_by_families = "view_products_by_families";
 
    // object properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;
	
	private $results;

	
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

	/**
	* Save atrribute(s)
	* @param $data array
	*/
	public function save_attribute($data){
		$timestamp = date("Y-m-d G:i:s", mktime());
		//check if Product Attribute already exists
		if( $this->get_attribute($data) != 0){
			$current_attribute_data = $this->get_attribute($data);
			extract($data);
			if( $attribute == "Description" || $attribute == "Acquired_Site" || $attribute == "Entity_ID" ){
				$stmt = $this->conn->prepare("UPDATE `".$this->general."` SET $attribute = :attribute_value, `Modified_Datetime` = '".$timestamp."' WHERE `ID` = :id");
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->bindParam(':attribute_value', $attribute_value, PDO::PARAM_STR);
				$stmt->execute();
				if($stmt->rowCount() == 1){
					return true;
				}else{
					return false;
				}				
			}else{
				if( !isset($attribute_type) ){
					$stmt = $this->conn->prepare("UPDATE `".$this->attributes."` SET `Product_Attribute_Value` = :attribute_value,  `Modified_Datetime` = '".$timestamp."' WHERE `Product_Attribute` = :attribute AND `Product_ID` = :id");
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->bindParam(':attribute', $attribute, PDO::PARAM_STR);
					$stmt->bindParam(':attribute_value', $attribute_value, PDO::PARAM_STR);					
				}else{
					$stmt = $this->conn->prepare("UPDATE `".$this->attributes."` SET `Unit_Measurement` = :attribute_value,  `Modified_Datetime` = '".$timestamp."' WHERE `Product_Attribute` = :attribute AND `Product_ID` = :id");
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->bindParam(':attribute', $attribute, PDO::PARAM_STR);
					$stmt->bindParam(':attribute_value', $attribute_value, PDO::PARAM_STR);						
				}

				$stmt->execute();
				if($stmt->rowCount() == 1){
					
					try {					
						$log_data = array("action"=>"Update Product Attribute","object"=>$data['attribute'],"previous_data"=>$current_attribute_data['Product_Attribute_Value'],"updated_data"=>$data["attribute_value"],"user"=>$data['user'] );
						$this->log_admin_action($log_data);
					} catch (Exception $e) {
						die("Could not log action");
					}
				
					return true;
				}else{
					return false;
				}					
			}		
		}else{ // add new Product Attribute
			if( $this->add_attribute($data) ){
					try {					
						$log_data = array("action"=>"Add Product Attribute","object"=>$data['attribute'],"previous_data"=>"","updated_data"=>$data["attribute_value"],"user"=>$data['user'] );
						$this->log_admin_action($log_data);
					} catch (Exception $e) {
						die("Could not log action");
					}
					
				return true;
			}else { return false; }
		}
		
	}
	
	/**
	* get_attribute
	* Retrieve attribute details for specified Product ID and Attribute 
	* @param $data array
	* @param $count_only bool If set to true only the number of rows retrieve will be returned; otherwise the result set will be returned
	*/
	public function get_attribute( $data ){
		extract($data);
		$stmt = $this->conn->prepare("SELECT `ID`,`Product_ID`,`Product_Attribute`,`Product_Attribute_Value` FROM `".$this->attributes."` WHERE `Product_ID`  = :id AND `Product_Attribute`= :attribute");
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->bindValue(':attribute', $attribute, PDO::PARAM_STR);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();
		$row = $stmt->fetch();
		if( $stmt->rowCount() == 1 ){
			return $row;
		}else{
			return 0;
		}

	}		
	
	/**
	* add_attribute
	* Add new Product Attribute
	* @param $data array 
	*/
	public function add_attribute( $data ){
		
		    $stmt = $this->conn->prepare("INSERT INTO `".$this->attributes."` (Product_ID,Product_Attribute,Product_Attribute_Value) VALUES (:Product_ID,:Product_Attribute, :Product_Attribute_Value)");
			$stmt->bindParam(':Product_Attribute',$product_attribute, PDO::PARAM_STR);
			$stmt->bindParam(':Product_Attribute_Value',$product_attribute_value, PDO::PARAM_STR);
			$stmt->bindParam(':Product_ID',$product_id, PDO::PARAM_STR);
			
			$product_attribute =  $data['attribute'];
			$product_attribute_value = $data['attribute_value'];
			$product_id = $data['id'];
			//$user = $data['user'];
			if($stmt->execute()){
				return true;
			}else{ return false; }
	}	
	
	/**
	* log_admin_action
	* Log action made by an admin ( EE user )
	* @param $data array 
	*/
	public function log_admin_action( $data ){

		    $stmt = $this->conn->prepare("INSERT INTO `".$this->admin_actions."` (Admin, Action, Object,Previous_Data, Updated_Data) VALUES (:Admin, :Action, :Object,:Previous_Data, :Updated_Data)");
			$stmt->bindParam(':Admin', $user);
			$stmt->bindParam(':Action',$action);
			$stmt->bindParam(':Object',$object);
			$stmt->bindParam(':Previous_Data',$previous_data, PDO::PARAM_STR);
			$stmt->bindParam(':Updated_Data', $updated_data, PDO::PARAM_STR);
			
			// set values
			$user = $data['user'];
			$action = $data['action'];
			$object = $data['object'];
			$previous_data = $data['previous_data'];
			$updated_data = $data['updated_data'];
			
			$stmt->execute();			
	}
	
}