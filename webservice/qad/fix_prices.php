<?php 

	if (file_exists('../../config/configWS.inc.php'))		
		require_once('../../config/configWS.inc.php');
	else
		return "ConfigWS not found";
	
	
		$sql = "SELECT distinct(id_product) FROM ps_product";
		if ($row = Db::getInstance()->ExecuteS($sql)){
					
    		foreach($row as $key => $value)
    		{
    		

    			//echo $value['id_product']."<br/>";
    		
    			$sql2 = 'SELECT SUM(quantity) as quantity FROM ps_stock_available
					WHERE id_product = '.(int)$value['id_product'].'
					AND id_product_attribute <> 0 
					-- AND id_shop = 1 and depends_on_stock = 1
					AND id_shop = 1';
				
				if ($row2 = Db::getInstance()->ExecuteS($sql2)){
					echo "id ". $value['id_product']."<br>";
					

					
					//print_r($row2);	
					$sql3 = "UPDATE ps_stock_available set quantity = ".$row2[0]['quantity']." WHERE id_product = ".$value['id_product']." and id_product_attribute = 0; ";
					//echo $sql3."<br>";
					//exit;
					Db::getInstance()->execute($sql3);

				}else{
					echo "err";
				}	

    		}



    	} else { 
				echo "error";
		}		
		
		/*$sql = "SELECT id_product FROM "._DB_PREFIX_."product WHERE reference = '$new_code'";			
		if ($row = Db::getInstance()->getRow($sql))
				return $row['id_product'];
    		else
				return false;
			


		$total_quantity = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT SUM(quantity) as quantity
			FROM '._DB_PREFIX_.'stock_available
			WHERE id_product = '.(int)$this->id_product.'
			AND id_product_attribute <> 0 '.
			StockAvailable::addSqlShopRestriction(null, $id_shop)
		);*/



