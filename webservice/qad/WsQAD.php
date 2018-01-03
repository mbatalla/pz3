<?php 
	if (file_exists('../../config/configWS.inc.php'))		
		require_once('../../config/configWS.inc.php');
	else
		return "ConfigWS not found";
	
	class WsQAD 
	{		
			
		/**			
				Description: Find a product matching short QAD code
				table  		 : product
				field  		 : reference
				return 		 : id_product or false if not found

		*/
		public function find_product($CodigoCorto)
			{						
				//Code without shoe size
				
				//$file = 'logfile.log'
				//file_put_contents($file, "\n PROD cod corto   ". $CodigoCorto   ." \n\n", FILE_APPEND | LOCK_EX);
				$sql = "SELECT id_product FROM "._DB_PREFIX_."product WHERE reference = '$CodigoCorto'";			
				if ($row = Db::getInstance()->getRow($sql))
    					return $row['id_product'];
		    		else
	    				return false;
			}
		

		/*Update a existing product*/
		public function update_product($id_product, $Marca, $Categoria, $CodigoQAD, $CodigoCorto, $Descripcion, $CodColor, $CodCuero, $DscColor, $DscCuero, $Numero)
			{
			
				$match_codes = $CodigoCorto == substr($CodigoQAD, 0, 4);

				$file = 'logfile.log';
				file_put_contents($file, "\n PROD cod corto   ". $CodigoCorto  ." cod qad  ". substr($CodigoQAD, 0, 4)  ." \n\n", FILE_APPEND | LOCK_EX);


				// if ( $match_codes ) 
				//	{
		
						//Find id color internal attr
		    		$id_color = $this->findColorId($CodColor, $DscColor);
		    		$id_size = $this->findSize($Numero);
		    		$this->create_combinations($id_product, $CodigoQAD, $id_color, $id_size);	

			    	// If no returns before, then we are OK	
			    	return "success";		    		
				//	}	
				//else 
				//	{
				//		return "error";
				//	}
			
			}

		/*Create a new product*/
		public function create_product($Marca, $Categoria, $CodigoQAD, $CodigoCorto, $Descripcion, $CodColor, $CodCuero, $DscColor, $DscCuero, $Numero)
		{
			
			$id_manufacturer 	= $this->findManufacturerId($Marca); //get id	
			$id_category 	 		= $this->findCategoryId($Categoria); // get id

			$match_codes = $CodigoCorto === substr($CodigoQAD, 0, 4);

			if ( $match_codes ) 
				{
				
						/**
								Insert into table product
						*/
						$new_code = substr($CodigoQAD,0 ,-3);
						Db::getInstance()->insert('product', array(
			    				'id_tax_rules_group'   => 	1,
			    				'available_date'		=>	'0000-00-00',
			    				'reference' =>  $new_code,
			    				'date_add'	=>	pSQL(date('Y-m-d H:i:s')),
	    						'date_upd'	=>	pSQL(date('Y-m-d H:i:s')),  
	    						'id_category_default' => 2,
	    						'active' => 1,
	    						'redirect_type' => 404,  	
	    						'indexed' => 1,
	    						'advanced_stock_management' => 1,			
							));
						$product_id = Db::getInstance()->Insert_ID(); //return inserted id

						//If cannot get last inserted id, cannot continue
							if( !isset($product_id) )
								return 'error'; 	

						$desc  = "MARCA: ".$Marca."<br/>";
						$desc .= "ARTICULO: ".$CodigoCorto."<br/>";
						$desc .= "COLOR: ".$DscColor."<br/>";
						$desc .= "LINEA: ".$Categoria."<br/>";
						$desc .= "ORIGEN: NACIONAL <br/>";
						$desc .= "TEMPORADA: ". $Categoria;

						$data_lang = array(
			                     'id_product' => $product_id, 
			                     'id_lang' => Configuration::get('PS_LANG_DEFAULT'),
			                     'id_shop' => Configuration::get('PS_SHOP_DEFAULT'),
			                     'description' => $desc,
			                     'link_rewrite' => 'temp-url',
			                     'name' => pSQL($Marca . " " . $Categoria)//pSQL($CodigoCorto), //validate if correct

		                     );

						/**
								Insert into table product_lang
						*/	
		    		Db::getInstance()->insert('product_lang', $data_lang);
		    		$product_lang = Db::getInstance()->Insert_ID();

		    		//If cannot get last inserted id, cannot continue
							if( !isset($product_lang) )
									return 'error'; 

		    		$data_shop = array(
		                     'id_product' => $product_id,                      
		                     'id_shop' => Configuration::get('PS_SHOP_DEFAULT'),
		                     'id_category_default' => 2,
		                     'id_tax_rules_group' => 1,
		                     'available_date'		=>	'0000-00-00',
						    				 'date_add'	=>	pSQL(date('Y-m-d H:i:s')),
				    						 'date_upd'	=>	pSQL(date('Y-m-d H:i:s')),
				    						 'redirect_type' => 404,
				    						 'indexed' => 1,
				    						 'advanced_stock_management' => 1,
		                     );
				
						/**
								Insert into table product_shop
						*/
		    		Db::getInstance()->insert('product_shop', $data_shop);
		    		$product_shop = Db::getInstance()->Insert_ID();

		    		//If cannot get last inserted id, cannot continue
							if( !isset($product_shop) )
									return 'error'; 


						/**						
							Here we generate all category relationships to a product
						*/		
						$this->set_category($id_category, $product_id);				


						//Find id color internal attr
		    		$id_color = $this->findColorId($CodColor, $DscColor);
		    		$id_size = $this->findSize($Numero);
		    		$this->create_combinations($product_id, $CodigoQAD, $id_color, $id_size);

						//If there's no return before here, we are OK
						return "success";
			
				}		
			else 
				{
					return "not matched";
				}
			
		}


		private function create_combinations($id_product, $CodigoQAD, $id_color, $id_size)
			{

				//Check if product_attribute already exists

				$sql = "SELECT id_product_attribute FROM "._DB_PREFIX_."product_attribute WHERE reference = '$CodigoQAD'";
				if ($row = Db::getInstance()->getRow($sql))
					{
    				$new_attr_id =  $row['id_product_attribute'];
					}
    		else
    			{    				
						/*Now we create a color relationship*/
		    		$prod_attr = array(
		                     'id_product' => $id_product,                      
		                     'available_date'		=>	'0000-00-00',	
		                     'reference' => $CodigoQAD,	  
		                     'default_on' => 0 // Set combination as not default. Status depends on stock
		                     );

		    		/**
								Insert into table product_attribute
						*/	
		    		Db::getInstance()->insert('product_attribute', $prod_attr);
		    		$new_attr_id = Db::getInstance()->Insert_ID();

		    			//If cannot get last inserted id, cannot continue
							if( !isset($new_attr_id) )
									return 'error'; 	
    			}
    			

    			//Clear all combis
    			$sql = "DELETE FROM "._DB_PREFIX_."product_attribute_combination WHERE id_product_attribute = $new_attr_id";
					if (!Db::getInstance()->execute($sql))
						return 'error'; 

  			//Check if product_attribute_combination already exists (color)
				$sql = "SELECT * FROM "._DB_PREFIX_."product_attribute_combination WHERE id_product_attribute = $new_attr_id and id_attribute = $id_color";
				if ($row = Db::getInstance()->getRow($sql))
					{
    				$comb_id =  $row['id_product_attribute'];
					}
    		else
    			{			

		    		/**
		    			Insert into table product_attribute_combination (color combination)
		    		*/
		    		Db::getInstance()->insert('product_attribute_combination', array(
		    													'id_attribute' => $id_color,
		    													'id_product_attribute' => $new_attr_id,
		    												));
		    		$comb_id = Db::getInstance()->Insert_ID();

		    			//If cannot get last inserted id, cannot continue
							if( !isset($comb_id) )
									return 'error'; 
    			}		

  			//Check if product_attribute_combination already exists (size)
				$sql = "SELECT * FROM "._DB_PREFIX_."product_attribute_combination WHERE id_product_attribute = $new_attr_id and id_attribute = $id_size";
				if ($row = Db::getInstance()->getRow($sql))
					{
    				$comb_id =  $row['id_product_attribute'];
					}
    		else
    			{
  						/**
			    			Insert into table product_attribute_combination (size combination)
			    		*/
			    		Db::getInstance()->insert('product_attribute_combination', array(
			    													'id_attribute' => $id_size,
			    													'id_product_attribute' => $new_attr_id,
			    												));
			    		$comb_id = Db::getInstance()->Insert_ID();

			    		//If cannot get last inserted id, cannot continue
							if( !isset($comb_id) )
								return 'error'; 	
	    				
	    		}

					
				//Check if product_attribute_shop already exists
				$sql = "SELECT id_product_attribute FROM "._DB_PREFIX_."product_attribute_shop WHERE id_product_attribute = $new_attr_id";
				if ($row = Db::getInstance()->getRow($sql))
					{
    				$comb_shop_id =  $row['id_product_attribute'];
					}
    		else
    			{

    				/**
								Insert into table product_attribute_shop
						*/			
		    		Db::getInstance()->insert('product_attribute_shop', array(
		    									'id_product_attribute' => $new_attr_id,
		    									'id_shop' => Configuration::get('PS_SHOP_DEFAULT'),
		    									'default_on' => 0,
		    								));
	    			$comb_shop_id = Db::getInstance()->Insert_ID();

		    			//If cannot get last inserted id, cannot continue
							if( !isset($comb_shop_id) )
									return 'error'; 
		    	
		    	}


	    	//Check if product_attribute_shop already exists
				$sql = "SELECT id_stock FROM "._DB_PREFIX_."stock WHERE id_product_attribute = $new_attr_id and id_product = $id_product";
				if ($row = Db::getInstance()->getRow($sql))
					{
    				$stock_id =  $row['id_stock'];
					}
    		else
    			{
    				/**
								Now we create a new record for stock management 
								First we make a new insert into stock table
						*/			
		    		Db::getInstance()->insert('stock', array(
		    									'id_warehouse' => 1, //This is our default shop, IN CASE OF MULTISTORE, THIS WON'T WORK!
		    									'id_product' => $id_product,
		    									'id_product_attribute' => $new_attr_id,
		    									'physical_quantity' => 0,
		    									'usable_quantity' => 0,
		    									'price_te' => 0,
		    								));
	    			$stock_id = Db::getInstance()->Insert_ID();

		    			//If cannot get last inserted id, cannot continue
							if( !isset($stock_id) )
									return 'error'; 		
    			
    			}	
						

				//Check if product_attribute_shop already exists
				$sql = "SELECT id_stock_available FROM "._DB_PREFIX_."stock_available WHERE id_product_attribute = $new_attr_id and id_product = $id_product";
				if ($row = Db::getInstance()->getRow($sql))
					{
    				$stock_id =  $row['id_stock_available'];
					}
    		else
    			{

    				/**
								This record is for depends_on_stock value.
								This should be created just one time.
						*/			
		    		$sql = "SELECT count(id_stock_available) FROM "._DB_PREFIX_."stock_available WHERE id_product_attribute = 0 and id_product = $id_product";
						if (Db::getInstance()->getValue($sql) == 0)
			    		Db::getInstance()->insert('stock_available', array(
			    									'id_product' => $id_product,
			    									'id_product_attribute' => 0,
			    									'id_shop' => Configuration::get('PS_SHOP_DEFAULT'),
			    									'depends_on_stock' => 1,
			    								));


    				/**
								Now we create a new record for stock management 
								First we make a new insert into stock_available table
						*/			
		    		Db::getInstance()->insert('stock_available', array(
		    									'id_product' => $id_product,
		    									'id_product_attribute' => $new_attr_id,
		    									'id_shop' => Configuration::get('PS_SHOP_DEFAULT'),
		    									'depends_on_stock' => 0,
		    								));
	    			$stock_available_id = Db::getInstance()->Insert_ID();
		    			//If cannot get last inserted id, cannot continue
							if( !isset($stock_available_id) )
									return 'error';
				    			
				  }				
			}

		private function findSize($Numero)
			{

				$size =  substr($Numero,0,2);
				$sql = "SELECT id_attribute FROM "._DB_PREFIX_."attribute_lang WHERE name = '$size'";
				
				if ($row = Db::getInstance()->getRow($sql))
    			return $row['id_attribute'];
    		else
    			return false;
    		
			}


		/*Edit manufacturer's product*/
		private function setColor($CodColor, $DscColor)
			{
			
				//this is the attribute id
				$color_id = findColorId($CodColor, $DscColor);

				$sql = "UPDATE "._DB_PREFIX_."product SET id_manufacturer = ". $manufacturer_id. " WHERE id_product = '$product_id";
				if (!Db::getInstancse()->execute($sql))
	    		return false; //Error al actualizar marca
	    	else
	    		return true;
			}


		/*Find a manufacturer, if not exists then create a new one*/
		private function findOneColorId($CodColor)
			{			
				$sql = "SELECT id_attribute FROM "._DB_PREFIX_."attribute WHERE color = '$CodColor'";
				if ($row = Db::getInstance()->getRow($sql))
    			return $row['id_attribute'];
    		else 
    			return false;
    		
			}


		/*Find a manufacturer, if not exists then create a new one*/
		private function findColorId($CodColor, $DscColor)
			{			
				$sql = "SELECT id_attribute FROM "._DB_PREFIX_."attribute WHERE color = '$CodColor'";
				if ($row = Db::getInstance()->getRow($sql))
    			return $row['id_attribute'];
    		else 
    			return $this->createColor($CodColor, $DscColor);
	    		
			}

		private function createColor($CodColor, $DscColor)
			{
				
				$position = $this->getLastInsertedId("position", "attribute", " WHERE id_attribute_group = 3 ");
			
				Db::getInstance()->insert('attribute', array(
	    				'id_attribute_group' => 3,
	    				'color'		=>	pSQL($CodColor),
	    				'position'	=>	$position,    				
					));
				//return inserted id
				$color_id = Db::getInstance()->Insert_ID();
				
				$data_lang = array(
	                     'id_attribute' => $color_id, 
	                     'id_lang' => Configuration::get('PS_LANG_DEFAULT'),
	                     'name' => pSQL($name),
	                     );

				$data_shop = array(
	                     'id_attribute' => $color_id, 
	                     'id_shop' => Configuration::get('PS_SHOP_DEFAULT'),
	                     );

				
	    		// Now insert the manufacturer lang data
	    		Db::getInstance()->insert('attribute_lang', $data_lang);
	    		Db::getInstance()->insert('attribute_shop', $data_shop);
				
				return $color_id;
			}


		/**
		 	Below we have all manufacturer related actions
		*/
		
		/*Edit manufacturer's product*/
		/*private function set_manufacturer($manufacturer, $product_id){
			
			$manufacturer_id = $this->findManufacturerId($manufacturer);

			$sql = "UPDATE "._DB_PREFIX_."product SET id_manufacturer = ". $manufacturer_id. " WHERE id_product = $product_id";
			if (!Db::getInstance()->execute($sql))
    			return false; //Error al actualizar marca
    		else
    			return true;
		}*/


		/*Find a manufacturer, if not exists then create a new one*/
		private function findManufacturerId($name)
			{			
				$sql = "SELECT id_manufacturer FROM "._DB_PREFIX_."manufacturer WHERE name = '$name'";
				if ($row = Db::getInstance()->getRow($sql))
	    		return $row['id_manufacturer'];
	    	else 
	    		return $this->createManufacturer($name);
	    		
			}

	
		private function createManufacturer($name)
			{
				
				Db::getInstance()->insert('manufacturer', array(
	    				'name'      => 	pSQL($name),
	    				'date_add'	=>	pSQL(date('Y-m-d H:i:s')),
	    				'date_upd'	=>	pSQL(date('Y-m-d H:i:s')),
	    				'active'	=>	1
					));
				//return inserted id
				$new_id = Db::getInstance()->Insert_ID();
				
				$data_lang = array(
	                     'id_manufacturer' => $new_id, 
	                     'id_lang' => Configuration::get('PS_LANG_DEFAULT')
	                     );

				//We user just one shop
				$data_shop = array(
	                     'id_manufacturer' => $new_id, 
	                     'id_shop' => Configuration::get('PS_SHOP_DEFAULT'),
	                     );
	    		// Now insert the manufacturer lang data
	    		Db::getInstance()->insert('manufacturer_lang', $data_lang);
	    		Db::getInstance()->insert('manufacturer_shop', $data_shop);
				
				return $new_id;
			}

		/**

			Description : This function insert recursively all parent categories from a product
			table 			: category_product
			field 			: all
			return 			: true or false

		*/


		private function set_category($category_id, $product_id)
			{
				
				//CHeck if relations exists
				$sql = "SELECT * FROM "._DB_PREFIX_."category_product WHERE id_category = $category_id AND id_product = $product_id";
				$row = Db::getInstance()->getRow($sql);
					if (!$row){
						//Insert the new relationship
							Db::getInstance()->insert('category_product', array(
				    				'id_category' => $category_id,
				    				'id_product'	=> $product_id,
				    				'position'	  =>	0	
								));
					}
				

				// Now check if category has parent
				$sql = "SELECT id_parent FROM "._DB_PREFIX_."category WHERE id_category = $category_id";
				if ($row = Db::getInstance()->getRow($sql))
					{
	    			if($row['id_parent'] != 2)
	    			{
	    				$this->set_category($row['id_parent'], $product_id); //Call itself until parent is first level
	    			}
	    		}	
	    		
			}

		/**
		
				Description: Find a category_id by name.
				table  		 : category_lang
				field  		 : id_category
				return 		 : Return existing id_category or new created category_id

		*/

		private function findCategoryId($name)
			{			
				$sql = "SELECT id_category FROM "._DB_PREFIX_."category_lang WHERE description LIKE '%$name%'";
				if ($row = Db::getInstance()->getRow($sql))
	    		return $row['id_category'];
	    	else
	    		return $this->createCategory($name);
			}


		//Create a new category into db
		private function createCategory($name)
			{				
				$object = new Category();
				$object->name = array((int)Configuration::get('PS_LANG_DEFAULT') => $name);
				$object->description =  array((int)Configuration::get('PS_LANG_DEFAULT') => $name);
				$object->id_parent = Configuration::get('PS_HOME_CATEGORY');
				$object->link_rewrite = array((int)Configuration::get('PS_LANG_DEFAULT') =>  'temp-url');
				$object->active = 0;
				$object->add();	
				
				return $this->getLastInsertedId("id_category", "category", "");
			}

		/*
		
		*/

		private function getLastInsertedId($item, $table, $condition = "")
			{
				$sql = "SELECT max($item) FROM "._DB_PREFIX_."$table". $condition ." LIMIT 1";
				if ($row = Db::getInstance()->getRow($sql))
    			return $row['last'];
			}



		private function findRelatedAttrs($id_product)
			{
				$sql = "SELECT id_product_attribute FROM "._DB_PREFIX_."product_attribute WHERE id_product = $id_product";
				$ids = array();	
				if ($results = Db::getInstance()->ExecuteS($sql)){
			    foreach ($results as $row){
			       $ids[] =  $row['id_product_attribute'];
			    }
				}

			  return $ids;		   
			}

		

		/**

			Update the price from a product	

		*/

			public function update_price($id_product, $PrecioLista )
				{

					//We must save price without tax. Prestashop will calculate tax automatically
					//$file = 'logfile.log';	
					//$file = 'logfile.log';
					//file_put_contents($file, "\n PRICE cod corto   ". $PrecioLista  ." \n\n", FILE_APPEND | LOCK_EX);

					$price = $PrecioLista / 1.19;
					$query = "UPDATE "._DB_PREFIX_."product_shop SET price = $price WHERE id_product = $id_product";
					//$status = Db::getInstance()->execute($query);
					if(!Db::getInstance()->execute($query))
						return "error";

					//file_put_contents($file, "\n query 1 ". $query  ." \n\n" , FILE_APPEND | LOCK_EX);

					$query = "UPDATE "._DB_PREFIX_."product SET price = $price WHERE id_product = $id_product";
					//$status = Db::getInstance()->execute($query);
					if(!Db::getInstance()->execute($query))
						return "error";

					//file_put_contents($file, "\n query 2 ". $query  ." \n\n" , FILE_APPEND | LOCK_EX);					
					return "success";					

				}


			// Find the combination reference using $CodigoQAD in product_attribute
			private function findProductAttribute($CodigoQAD)
				{

					$sql = "SELECT id_product_attribute FROM "._DB_PREFIX_."product_attribute WHERE reference = '$CodigoQAD'";
					if ($row = Db::getInstance()->getRow($sql))
	    			return $row['id_product_attribute'];

				}	


		/**
		
			Modify available stock from a product
			If product has stock 0, then is deactivated

		*/		

		public function update_stock($id_product, $CodColor, $CodigoCorto, $CodigoQAD, $Numero, $Stock)
			{				
				//Get the attribute id
				$id_product_attribute = $this->findProductAttribute(strtoupper($CodigoQAD));
				
				//If combination is found
				if($id_product_attribute)
					{											
						//$file = 'logfile.log';	
						$query = "UPDATE "._DB_PREFIX_."stock SET physical_quantity = $Stock, usable_quantity = $Stock WHERE id_product = $id_product AND id_product_attribute = $id_product_attribute";					
						if (!Db::getInstance()->execute($query)) return "error";

						$query = "UPDATE "._DB_PREFIX_."stock_available SET quantity = $Stock, depends_on_stock = 0 WHERE id_product = $id_product AND id_product_attribute = $id_product_attribute";
						if (!Db::getInstance()->execute($query)) return "error";

    				// Calculate SUM of all combinations
    				$query = "SELECT SUM(quantity) as quantity FROM ps_stock_available WHERE id_product = $id_product AND id_product_attribute <> 0 ";
    				file_put_contents($file, "\n sum ". $query  ." \n\n" , FILE_APPEND | LOCK_EX);
						$sum = Db::getInstance()->getRow($query);
	    			
	    			//Update total of stock for all combinations
	    			$query = "UPDATE ps_stock_available set quantity = ".$sum['quantity']." WHERE id_product = $id_product and id_product_attribute = 0";
						//file_put_contents($file, "\n query ". $query  ." \n\n" , FILE_APPEND | LOCK_EX);

						if (!Db::getInstance()->execute($query)) return "error";
		
    				//$this->modify_default_size($id_product, $id_product_attribute, $Stock);
						

					}

				else
					{
					return "error";
					}

			}


		/*public function modify_default_size($id_product, $id_product_attribute, $stock)
			{
				//$file = 'logfile.log';	
				$status = ($stock > 0) ? 1 : 0;
				
				$query = "UPDATE ps_product_attribute set default_on = $status WHERE id_product = $id_product and id_product_attribute = $id_product_attribute";							
				if (!Db::getInstance()->execute($query)) return "error";				
//file_put_contents($file, "\n query 1 ". $query  ." \n\n" , FILE_APPEND | LOCK_EX);


				$query = "UPDATE ps_product_attribute_shop set default_on = $status WHERE id_product_attribute = $id_product_attribute";	
				if (!Db::getInstance()->execute($query)) return "error";				
//file_put_contents($file, "\n query 2". $query  ." \n\n" , FILE_APPEND | LOCK_EX);
				return "success";
		
			}	*/

		public function activate_deactivate($id_product)
			{

				return false;

			}	

		private function logger($string)		
			{
				$file = 'logfile.log';
				file_put_contents($file, "\n --- ". $string  ." \n\n" , FILE_APPEND | LOCK_EX);
			}	

}//End class
?>
