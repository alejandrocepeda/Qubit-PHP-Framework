
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
                <div class="clearfix"></div>
                </h1>
                

                <form action="<?php echo $this->url(array(action => 'update'),array(id => $this->id)) ?>" method="post" >
                
                <div class="form-inline">
                
                        <label for="rut">Rut</label>
                        <input required name="rut" value="<?php echo $this->id ?>" style="width: 150px; margin-left:10px "  type="number" class="form-control" id="rut" placeholder="Rut">
                        &nbsp;&nbsp;
                        <label for="dv">Dv</label>
                        <input required name="dv" value="<?php echo $this->dv ?>"  style="width: 50px; margin-left:10px" maxlength="1" type="text" class="text-center form-control input-mini" id="dv" placeholder="Dv">
                </div>
                <br>    
                
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input required name="nombre" value="<?php echo $this->nombre ?>" type="text" class="form-control" id="nombre" placeholder="Nombre">
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input required name="apellido" value="<?php echo $this->apellido ?>" type="text" class="form-control" id="apellido" placeholder="Apellido">
                </div>

                <div class="form-group">
                    <label for="password">Contrasela</label>
                    <input <?php if ($this->id == 0) { echo " required ";}  ?> pattern=".{5,10}" name="password" type="password" class="form-control" id="password" placeholder="Contraseña">
                    <?php if ($this->id > 0)  { ?>
                    <small>Dejar en blanco si no hay cambios. Debe tener entre 5 y 10 digitos de longitud</small>
                    <?php } ?>
                </div>

                <div class="checkbox">
                    <label>
                        <?php echo $this->FormCheckbox('administrador',array('value' => '1','checkedValue' => $this->administrador)) ?> Administrador
                    </label>
                </div>

                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a href="<?php echo $this->url(array(action => 'index')) ?>" class="btn btn-danger">Regrear</a>
                </form>

                
            </div>

        </div>
        </div>
    </div>
</div>

