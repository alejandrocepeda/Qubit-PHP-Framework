<?php $this->JQuery()->onLoadCaptureStart()  ?>
/*jquery onload*/
    
  $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
    event.preventDefault(); 
    event.stopPropagation(); 
    $(this).parent().siblings().removeClass('open');
    $(this).parent().toggleClass('open');
  });

<?php $this->JQuery()->onLoadCaptureEnd()  ?>


<!-- Fixed navbar -->
<div style="border-bottom:#70C600 4px solid  " class="navbar-inverse navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="header_gradient navbar-header">
      <button  type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">App Qubit Framerwork</a>
    </div>


    <?php
    $controllername = $this->getControllerName();

    if ($controllername != 'auth'){
    ?>

    <div  style="" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li <?php if ($controllername == 'home'){ echo "class='active'";}  ?>>
          <a href="<?php echo $this->url(array('controller' => 'home')) ?>">Inicio</a>
        </li>
     

        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Mantenimientos <b class="caret"></b></a>
          <ul class="dropdown-menu">
              <li><a href="<?php echo $this->url(array('controller' => 'usuarios')) ?>">Usuarios</a></li>
              <li><a href="#">Clientes</a></li>
              <li><a href="#">Proveedores</a></li>
          </ul>
        </li>
        <li>
          <a href="<?php echo $this->url(array('controller' => 'logout')) ?>">Cerrar Sesión</a>
        </li>
      </ul>
    </div>

    <?php } ?>

  </div>
</div>


