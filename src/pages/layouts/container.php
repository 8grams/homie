<?php $this->layout('main')?>

<?php $this->start('navbar') ?>
    <?=$this->e($navbar)?>
<?php $this->stop() ?>

<?php $this->start('footer') ?>
    <?=$this->e($footer)?>
<?php $this->stop() ?>

<?=$this->e($content)?>