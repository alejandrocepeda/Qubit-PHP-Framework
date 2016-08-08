

<h2 class="pull-left"><?php echo $this->message ?></h2>
<a class="btn btn-danger pull-right" href="<?php echo $this->HistoryBack() ?>">Regresar</a>

<div class="clearfix"></div>
<h2>Rastro</h2>
<p>

<?php

foreach ($this->e->getTrace() as $trace){ ?>
<?php if (isset($trace['file'])){ ?>
<p><strong><?php echo htmlspecialchars($trace['file'], ENT_NOQUOTES, 'UTF-8') . "(" . $trace ['line'] . ")" ?></strong></p>
        <p>La excepción se ha generado en el archivo <span class="italic"><?php echo $trace['file'] ?></span> en la línea: <span class="italic"><?php echo $trace['line'] ?></span>:</p>

        <ul class="exception_trace">
            <?php 
                $lines = file ( $trace ['file'] ); 
                $start = ($trace ['line'] - 4) < 0 ? 0 : $trace ['line'] - 4;
                $end = ($trace ['line'] + 2) > count ( $lines ) - 1 ? count ( $lines ) - 1 : $trace ['line'] + 2;
            ?>

            <?php for($i = $start; $i <= $end; $i ++): ?>
                <li <?php if ($i == $trace ['line'] - 1): ?> class="exception_highlight" <?php endif; ?>>
                    <strong><?php echo ($i + 1) ?></strong> <?php echo htmlspecialchars($lines [$i], ENT_NOQUOTES, 'UTF-8') ?>

                </li>
            <?php endfor; ?>
        </ul>

        <br>
    <?php } ?>
<?php } ?>

<a class="btn btn-danger pull-right" href="<?php echo $this->HistoryBack() ?>">Regresar</a>
<div class="clearfix"></div>
</p>