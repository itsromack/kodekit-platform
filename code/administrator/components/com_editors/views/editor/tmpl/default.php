<style src="media://com_editors/codemirror/css/docs.css" />
<?= var_export( $editors) ?>
<?= @template('default_script') ?>
<textarea id="<?= $name ?>" name="<?= $name ?>" cols="75" rows"25" class="editable"><?= $data ?></textarea>