<label for="<?php echo $id ?>"><?php echo $label ?></label>
<input type="text" class="form-control form-control-size-<?php echo $size ?> <?php echo $class ?>" id="<?php echo $id ?>" />
<?php if (!\System\Utils\Text::isEmpty($note)): ?>
<p class="help-block"><?php echo $note ?></p>
<?php endif ?>
<script type="text/javascript">
$(document).ready(function() { $("#<?php echo $id ?>").datetimepicker({
    format: 'YYYY/MM/DD',
}); });
</script>
