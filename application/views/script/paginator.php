<nav>
<div class="text-center">
<ul class="pagination">

<?php 
if ($this->_pagPrev>0){ ?>
    <li class="previous">
        <a href="<?php echo $this->url(array(query => array(pag =>$this->_pagPrev))); ?>"><i class="fa fa-chevron-left"></i></a>
    </li>
<?php } ?>

<?php    
while ($this->i<$this->_PageRange) {
    if ($this->pg>0 and $this->pg<=$this->pagtotal) {
        $class_active = ($this->pg==$this->page) ? ' class="active" ':'';
        ?>
        <li <?php echo $class_active ?>>
            <a href="<?php echo $this->url(array(query => array(pag => $this->pg))); ?>"><?php echo $this->pg ?></a>
        </li>
        <?php
        $this->i++;
    }
    if ($this->pg>$this->pagtotal){
        $this->i=$this->_PageRange;
    }
    $this->pg++;
}
?>

<?php 
if ($this->_pagNext<=$this->pagtotal) { ?>
    <li class="next">
        <a href="<?php echo $this->url(array(query => array(pag => $this->_pagNext)));  ?>"><i class="fa fa-chevron-right"></i></a>
    </li>
<? } ?>

</ul>
</div>
</nav>


