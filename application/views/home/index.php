
<?php $this->headLink()->CaptureStart() ?>
/*css style*/

.apu a{
	color:#fff;
	text-decoration: none;
}

#content-menu-secundary .page-header{
	border-bottom: 0 !important;
	padding-bottom: 0px !important;
}

#content-menu-secundary .page-header > h2 > small{
	font-size: 14px;
}


<?php $this->headLink()->CaptureEnd() ?>

<?php $this->HeadScript()->CaptureStart() ?>
/*javascript*/
<?php $this->HeadScript()->CaptureEnd() ?>

<?php $this->JQuery()->onLoadCaptureStart()  ?>
/*jquery onload*/
<?php $this->JQuery()->onLoadCaptureEnd()  ?>

<div class="container" id="login-block">
<div class="row">
	<div class="col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
		<div class="page-icon-shadow animated bounceInDown"> </div>
            <div class="clearfix animated flipInY">
			<center>
			<h2>Bienvenidos <?php echo $this->nombre_completo ?></h2>
			
			</center>
		</div>
	</div>


</div>


<div id="content-menu-secundary">

<div class="page-header">
  <h2>Atención de Clientes<br><small>Para acceder a un sistema, haz clic en el icono de tu elección</small></h2>
</div>
<div class="clearfix"></div>


<div class="fu apt">

  <div class="gq gg ala">

    <div class="apu ano">
    <a href="#">
      	<div class="alz">
			<i class="fa fa-graduation-cap fa-3x"></i>
        	<h2 class="ani">
          	Acreditación
        	</h2>
      	</div>
    </a>
    </div>
  </div>
  <div class="gq gg ala">
    <div class="apu anr">
    <a href="#">
      	<div class="alz">
        
	        <i class="fa fa-cubes fa-3x"></i>
	        <h2 class="ani">
	          Apolo
	        </h2>
      	</div>
	</a>		
    </div>
  </div>
  <div class="gq gg ala">
    <div class="apu anp">
     <a href="#">
      <div class="alz">
       
	        <i class="fa fa-life-ring fa-3x"></i>
	        <h2 class="ani">
	          Emergencia
	        </h2>
        
      </div>
      </a>
    </div>
  </div>
  <div class="gq gg ala">
    <div class="apu anq">
     <a href="#">
      <div class="alz">
       
	        <i class="fa fa-cube fa-3x"></i>
	        <h2 class="ani">
	          Fai
	        </h2>
        
      </div>
      </a>
    </div>
  </div>
</div>


<div class="page-header">
  <h2>Soporte Institucional<br><small>Para acceder a un sistema, haz clic el el icoco de tu elección</small></h2>
</div>
<div class="clearfix"></div>

<div class="fu apt">

  <div class="gq gg ala">

    <div class="apu ano">
    <a href="#">
      <div class="alz">
        
			<i class="fa fa-puzzle-piece fa-3x"></i>
        	<h2 class="ani">
          	Otros
        	</h2>
        
      </div>
      </a>
    </div>
  </div>
  <div class="gq gg ala">
    <div class="apu anr">
    <a href="#">
      <div class="alz">
        
	        <i class="fa fa-check-square-o fa-3x"></i>
	        <h2 class="ani">
	          Otros
	        </h2>
        
      </div>
	</a>
    </div>
  </div>
  <div class="gq gg ala">
    <div class="apu anp">
    <a href="#">
      <div class="alz">
        
	        <i class="fa fa-envelope-o fa-3x"></i>
	        <h2 class="ani">
	          Otros
	        </h2>
       
      </div>
     </a>  
    </div>
  </div>
  <div class="gq gg ala">
    <div class="apu anq">
    <a href="#">
      <div class="alz">
        
	        <i class="fa fa-phone-square fa-3x"></i>
	        <h2 class="ani">
	          Otros
	        </h2>
        
      </div>
      </a>
    </div>
  </div>
</div>
</div>

