
<?php $this->headLink()->CaptureStart() ?>
/*css style*/
<?php $this->headLink()->CaptureEnd() ?>

<?php $this->HeadScript()->CaptureStart() ?>
/*javascript*/
<?php $this->HeadScript()->CaptureEnd() ?>

<?php $this->JQuery()->onLoadCaptureStart()  ?>
/*jquery onload*/
/*---------- For Placeholder on IE9 and below -------------*/
    $('input, textarea').placeholder();
    
    /*----------- For icon rotation on input box foxus -------------------*/    
    $('.input-field').focus(function() {
        $('.page-icon img').addClass('rotate-icon');
    });
    
    /*----------- For icon rotation on input box blur -------------------*/     
    $('.input-field').blur(function() {
        $('.page-icon img').removeClass('rotate-icon');
    });
    
   
    $('a.demo-btn').click(function(e) {
         e.preventDefault(); 
         var themeid =$(this).attr('data-demoid');
         var themename = window.location.href.split('?'); 
         window.location.href =   themename[0]+'?'+themeid ;
         
    });
        
        $("#btnlogin").click(function(e) {  
            e.preventDefault();  
      
            params = {
              'username': jQuery('#username').val(),
              'password' : jQuery('#password').val()
            };

            $.qubitAjax("<?php echo $this->url(array(controller => 'auth',action => 'authenticate')) ?>",{
              data  :  params
            });
        });
<?php $this->JQuery()->onLoadCaptureEnd()  ?>


        <div class="container" id="login-block">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
                   <div class="page-icon-shadow animated bounceInDown" > </div>
                   <div class="login-box clearfix animated flipInY">
                        
                        <div class="login-logo">
                            <a href="auth">
                                <!--<img class="img-circle" src="..." alt="Company Logo" />-->
                                <i style="color:#102800" class="fa fa-diamond fa-5x"></i>
                            </a>
                        </div> 

                        <hr />
                        <div class="login-form">
                            <!-- Start Error box -->
                                                <div id="mensaje-box" class="alert alert-danger hide">
                                                    <button type="button" class="close" data-dismiss="alert"> &times;</button>
                                                    <h4>Error!</h4>
                                                    <div id="mensaje-text"></div>
                        </div> <!-- End Error box -->
                                                
                            <form method="get" name="frmPrincipal" id="frmPrincipal" >
                                                    <input type="text" placeholder="Nombre de usuario" name="username" id="username" class="input-field" required/> 
                                                    <input type="password" name="password" id="password"  placeholder="Contraseña" class="input-field" required/> 
                                                    
                                                    
                                                    <!--<input type="checkbox" >
                                                    -->
                                                  
                                                    
                                                    <button id="btnlogin" type="button" class="btn btn-login">Entrar</button>                  
                        </form> 
                                                       
                        </div>  
                                        
                                          
                   </div>
                    
                   
                    
                </div>
            </div>


             
        </div>
     
       