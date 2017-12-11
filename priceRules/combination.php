<?php
require("../config/settings.inc.php");
require dirname(__FILE__).'/vendor/autoload.php';

$database = new Medoo(array(
    'database_type' => 'mysql',
    'database_name' => _DB_NAME_,
    'server' => _DB_SERVER_,
    'username' => _DB_USER_,
    'password' => _DB_PASSWD_
));


try {

  // Find and deactivate all products that doesn't have stock
  $products_without_stock = $database->query('
    (SELECT ps.id_product
      FROM ps_stock_available ps
        INNER JOIN ps_product p USING (id_product)
      WHERE p.active = 1
      GROUP BY ps.id_product
      HAVING SUM(ps.quantity) = 0)
    UNION
    (SELECT ps.id_product
      FROM ps_stock_available ps
        INNER JOIN ps_product_shop p USING (id_product)
      WHERE p.active = 1
      GROUP BY ps.id_product
      HAVING SUM(ps.quantity) = 0)
  ')->fetchAll();

  foreach ($products_without_stock as $query_row) {
    log_message('Modificando la tabla ps_product. Se seteará la columna active = 0 para el producto con id_product ' . $query_row['id_product']);
    $database->update( "ps_product", array("active" => 0), array("id_product" => $query_row['id_product']) );

    log_message('Modificando la tabla ps_product_shop. Se seteará la columna active = 0 para el producto con id_product ' . $query_row['id_product']);
    $database->update( "ps_product_shop", array("active" => 0), array("id_product" => $query_row['id_product']) );
  }


  // Find attributes with default_on = 1 and without stock
  $wrong_default_attributes = $database->query('
    (SELECT pa.id_product
     FROM ps_product_attribute pa
       INNER JOIN ps_stock_available ps USING (id_product_attribute)
       INNER JOIN ps_product p ON p.id_product = pa.id_product
     WHERE pa.default_on = 1
       AND ps.quantity = 0
       AND p.active = 1
     ORDER BY pa.id_product)
     UNION
     (SELECT pas.id_product
      FROM ps_product_attribute_shop pas
        INNER JOIN ps_stock_available ps USING (id_product_attribute)
        INNER JOIN ps_product p ON p.id_product = pas.id_product
      WHERE pas.default_on = 1
        AND ps.quantity = 0
        AND p.active = 1
      ORDER BY pas.id_product)
  ')->fetchAll();

  foreach ($wrong_default_attributes as $query_row) {
    $id_product = $query_row['id_product'];
    
    $new_default = $database->query("
      SELECT id_product_attribute
      FROM ps_stock_available
      WHERE quantity > 0
        AND id_product_attribute <> 0
        AND id_product = $id_product
      LIMIT 1
    ")->fetch();
    $id_new_attribute_default = $new_default ? $new_default['id_product_attribute'] : 0;

    log_message("Modificando la tabla ps_product_attribute. Se seteará la columna default_on = 0 para los atributos con id_product $id_product");
    $database->update( "ps_product_attribute", array("default_on" => false), array("id_product" => $id_product) );

    log_message("Modificando la tabla ps_product_attribute. Se seteará la columna default_on = 1 para el atributo con el id_product_attribute $id_new_attribute_default");
    $database->update( "ps_product_attribute", array("default_on" => 1), array("id_product_attribute" => $id_new_attribute_default) );

    log_message("Modificando la tabla ps_product_attribute_shop. Se seteará la columna default_on = 0 para los atributos con id_product $id_product");
    $database->update( "ps_product_attribute_shop", array("default_on" => false), array("id_product" => $id_product) );

    log_message("Modificando la tabla ps_product_attribute_shop. Se seteará la columna default_on = 1 para el atributo con el id_product_attribute $id_new_attribute_default");
    $database->update( "ps_product_attribute_shop", array("default_on" => 1), array("id_product_attribute" => $id_new_attribute_default) );

    log_message("Modificando la tabla ps_product. Se seteará la columna cache_default_attribute = $id_new_attribute_default para el producto con el id_product $id_product");
    $database->update( "ps_product", array("cache_default_attribute" => $id_new_attribute_default), array("id_product" => $id_product) );    

    log_message("Modificando la tabla ps_product_shop. Se seteará la columna cache_default_attribute = $id_new_attribute_default para el producto con el id_product $id_product");
    $database->update( "ps_product_shop", array("cache_default_attribute" => $id_new_attribute_default), array("id_product" => $id_product) );
  }

} catch (Exception $e) {
    log_message("ERROR: " . $e->getMessage());
}


// Common functions
function log_message($message) {
    echo date('Y/M/d H:i:s') . "    - $message\n";
}
