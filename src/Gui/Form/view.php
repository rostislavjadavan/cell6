<form method="post">
    <?php foreach($controls as $control): ?>
        <div class="form-group">
            <?php echo $control->render() ?>
        </div>
    <?php endforeach ?>

  <button type="submit" class="btn btn-default">Submit</button>
</form>
