<?php 

	if (file_exists('../../config/configWS.inc.php'))		
		require_once('../../config/configWS.inc.php');
	else
		return "ConfigWS not found";
	
	
		/**
			First we select all products and loop one by one.
		*/

		echo "Started at ...  " .date('Y-m-d H:i:s');	

		$sql  = " SELECT pa.id_product, pa.id_product_attribute, pa.default_on, ps.quantity"; 
		$sql .= " FROM ps_product_attribute pa, ps_stock_available ps ";
		$sql .= " WHERE pa.id_product_attribute = ps.id_product_attribute";
		
		if ($row = Db::getInstance()->ExecuteS($sql)){
					
    		foreach($row as $key => $value)
    		{
    		    		
    			/**
					Put everything as not default
				*/   
				$sql2 = "SELECT quantity FROM ps_stock_available WHERE id_product = ".$value['id_product']." and id_product_attribute = ".$value['id_product_attribute']."";	
				$row2 = Db::getInstance()->ExecuteS($sql2);


				//print_r($row);
				//exit;
				
				if($row2[0]['quantity'] > 0)
				{
					$sql3 = "UPDATE ps_product_attribute set default_on = 1 WHERE id_product = ".$value['id_product']." and id_product_attribute = ".$value['id_product_attribute']."";											
					$sql4 = "UPDATE ps_product_attribute_shop set default_on = 1 WHERE id_product_attribute = ".$value['id_product_attribute']."";	
				}
				else
				{
					$sql3 = "UPDATE ps_product_attribute set default_on = 0 WHERE id_product = ".$value['id_product']." and id_product_attribute = ".$value['id_product_attribute']."";
					$sql4 = "UPDATE ps_product_attribute_shop set default_on = 0 WHERE id_product_attribute = ".$value['id_product_attribute']."";											
				}

				Db::getInstance()->execute($sql3);
				Db::getInstance()->execute($sql4);	

				

    		}



    	} else { 
				echo "No products selected.";
		}	

		echo " <br/>Finished at ...  " .date('Y-m-d H:i:s');	
				



