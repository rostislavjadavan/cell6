<form class="form" method="post" data-uid="<?php echo $uid ?>">
    <?php foreach($controls as $control): ?>
        <div class="form-group">
            <?php echo $control->render() ?>
        </div>
    <?php endforeach ?>
    <?php foreach($buttons as $button): ?>
        <?php echo $button->render() ?>        
    <?php endforeach ?>
</form>
