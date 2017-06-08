<form method="post">
    <?php foreach($controls as $control): ?>
        <div class="form-group">
            <?php echo $control->render() ?>
        </div>
    <?php endforeach ?>
    <?php foreach($buttons as $button): ?>
        <?php echo $button->render() ?>        
    <?php endforeach ?>
</form>
