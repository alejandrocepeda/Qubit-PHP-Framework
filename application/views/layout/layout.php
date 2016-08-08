<?php echo $this->view->Doctype(); ?>
<html lang="en">
<head>
<?php echo $this->view->HeadTitle(); ?>
<?php echo $this->view->HeadMeta();  ?>
<?php echo $this->view->HeadLink(); ?>
<style>
         .header_gradient{
             background: #000;
         }
         .navbar-brand{
             color: #ffffff !important
         }
         .navbar-collapse{
             background: #000;
             color: #000;
         }
         .navbar-inverse{ background: #000; }
         #footer-text{font-size:16px;}
     </style>

</head>
<body>
<body class="fade-in">
	
	<?php echo $this->view->render('../layout/header')  ?>
	<?php echo $this->view->getmensajeconf() ?>
	<?php echo $this->getContent(); ?> 
</div>
<?php echo $this->view->HeadScript(); ?>
<?php echo $this->view->jQuery(); ?>
</body>
</html>


