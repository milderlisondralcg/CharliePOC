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
		//check if Product already exists
		if( $this->get_attribute($data) == 1){
			extract($data);
			if( $attribute == "Description" || $attribute == "Acquired_Site" || $attribute == "Entity_ID" ){
				//$sql = "UPDATE `".$this->general."` SET `:attribute` = :attribute_value WHERE `ID` = :id"; 
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
				$stmt = $this->conn->prepare("UPDATE `".$this->attributes."` SET `Product_Attribute_Value` = :attribute_value WHERE `Product_Attribute` = :attribute AND `Product_ID` = :id");
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->bindParam(':attribute', $attribute, PDO::PARAM_STR);
				$stmt->bindParam(':attribute_value', $attribute_value, PDO::PARAM_STR);
				$stmt->execute();
				if($stmt->rowCount() == 1){
					return true;
				}else{
					return false;
				}					
			}		
		}
		
	}
	
	private function get_attribute( $data ){
		extract($data);
		$stmt = $this->conn->prepare("SELECT COUNT(*) FROM `".$this->attributes."` WHERE `Product_ID`  = :id AND `Product_Attribute`= :attribute");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->bindParam(':attribute', $attribute, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->rowCount();

	}		
	
}