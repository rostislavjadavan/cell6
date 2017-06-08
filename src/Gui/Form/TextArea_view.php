<label for="<?php echo $id ?>"><?php echo $label ?></label>
<?php if (!\System\Utils\Text::isEmpty($note)): ?>
<p class="help-block"><?php echo $note ?></p>
<?php endif ?>

<textarea wrap="off" rows="<?php echo $rows?>" id="form-field-<?php echo $id ?>" name="<?php echo $id ?>" class="form-control form-control-size-<?php echo $size ?> <?php echo $class ?>"><?php echo htmlspecialchars($value) ?></textarea>
