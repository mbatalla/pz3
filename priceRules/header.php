<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">PZ - Descuentos</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Inicio <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="combination.php">Corrector combinaciones</a>
      </li>
      <li class="nav-item">
        <a href="product_info.php" class="nav-link">Product Info</a>
      </li>
    </ul>
    <a href="controllers/clear.php" onclick="return confirm('¿Estás seguro de que quieres elimniar todos los descuentos?')" class="btn btn-danger" style="margin-right: 20px"><span class="glyphicon glyphicon-trash"></span> Eliminar todas las reglas</a>
    <form class="form-inline my-2 my-lg-0" method="post" action="controllers/uploader.php" enctype="multipart/form-data">
      <input id="product_list" name="product_list" type="file" class="file" data-show-preview="false" >
    </form>
  </div>
</nav>

<?php  if($msg!=""){ ?>
<div class="row" style="margin-top: 20px;">
  <div class="col-md-3">
    <div class="clear"></div>
  </div>
  <div class="col-md-6"> 
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <strong>ATENCIÓN: </strong> <?php echo $msg; ?>
    </div>
  </div>
</div>
<?php } ?>