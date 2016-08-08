
<?php $this->headLink()->CaptureStart() ?>
/*css style*/
.page-title{
	color:#000
}

body{
	color:#000 !important;
}

.table > thead > tr > th {
    border-bottom:none !important;
}

.page-header {
    border-bottom: 1px solid #dddddd !important;
}
<?php $this->headLink()->CaptureEnd() ?>

<?php $this->HeadScript()->CaptureStart() ?>
/*javascript*/
function borrar(id){
  if (confirm("Esta seguro que desea eliminar el usuario # "+id+" ?")){
    window.location = '?action=delete&id='+id; 
  }
}
<?php $this->HeadScript()->CaptureEnd() ?>

<?php $this->JQuery()->onLoadCaptureStart()  ?>
/*jquery onload*/
<?php $this->JQuery()->onLoadCaptureEnd()  ?>


<div class="container" id="login-block">
<div class="row">
	<div class="col-sm-6 col-md-8 col-sm-offset-3 col-md-offset-4">
		<div class="page-icon-shadow animated bounceInDown"> </div>
            <div class="login-box clearfix animated flipInY">
				
			<div style="margin: 20px" >	
				<h1 class="page-header" >Usuarios
				
				<div class="pull-right">
				<a class="btn btn-mini btn-primary btn-xs" href="<?php echo $this->url(array(action => 'add'),array(id => 0)) ?>"><i class="fa fa-plus"></i></a>
				</div>

				<div class="clearfix"></div>

				</h1>
				

				<table class="table table-hover">
  				<thead>
  					<tr>
  						<th>#</th>
  						<th>Nombre</th>
  						<th>Apellido</th>
  						<th>Acciones</th>
  					</tr>
  				</thead>
  				<tbody>
  					<?php foreach ($this->rstusuarios as $row) { ?>
  					<tr>
							<td><?php echo $row->rut ?></td>
							<td><?php echo $row->nombre ?></td>
							<td><?php echo $row->apellido ?></td>
							<td>
								<a href="<?php echo $this->url(array(action => 'edit'),array(id => $row->rut)) ?>" class="btn btn-mini btn-success btn-xs">
									<i class="fa fa-pencil"></i>
								</a>

								<button type="button" onClick="borrar(<?php echo $row->rut ?>);" class="btn-mini btn btn-danger btn-xs">
									<i class="fa fa-times"></i>
								</button>

							</td>
  					</tr>
  						
  					<?php } ?>
  				</tbody>
				</table>

				<br>
				<?php echo $this->paginator ?>
			</div>

		</div>
		</div>
	</div>
</div>

