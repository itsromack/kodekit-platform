<? JHTML::_('behavior.calendar') ?>
<?= @helper('behavior.tooltip') ?>
<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://com_tournaments/css/admin/backend.css" />

<script>
if(Form && Form.Validator) {
    Form.Validator.add('validate-count', {
        errorMsg: <?= json_encode(@text('Please enter a higher number than 0.')) ?>,
        test: function(field){
            return field.get('value').toInt() > 0;
        }
    });
}
</script>

    <form action="<?= @route('id='.$newsfeed->id) ?>" method="post" class="-koowa-form">

        <div class="col100">
            <fieldset class="adminform">
                <legend><?= @text( 'Details' ) ?></legend>

                <table class="admintable">
                <tr>
                    <td width="170" class="key">
                        <label for="name">
                            <?= @text( 'Name' ) ?>
                        </label>
                    </td>
                    <td>
                        <input class="inputbox required" type="text" size="40" name="name" id="name" value="<?= $newsfeed->name ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="170" class="key">
                        <label for="name">
                            <?= @text( 'Alias' ) ?>
                        </label>
                    </td>
                    <td>
                        <input class="inputbox" type="text" size="40" name="slug" id="alias" value="<?= $newsfeed->slug ?>" />
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right" class="key">
                        <?= @text( 'Published' ) ?>:
                    </td>
                    <td>
                        <?= @helper('lib.koowa.template.helper.select.booleanlist', array('name' => 'enabled', 'selected' => $newsfeed->enabled)) ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="catid">
                            <?= @text( 'Category' ) ?>
                        </label>
                    </td>
                    <td>
                <?= @helper('admin::com.newsfeeds.template.helper.listbox.category', array(
                        'selected' => $newsfeed->catid,
                        'name'     => 'catid',
                        'attribs'  => array('class' => 'required')
                        )) ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="link">
                            <?= @text( 'Link' ) ?>
                        </label>
                    </td>
                    <td>
                        <input class="inputbox required validate-url" type="text" size="60" name="link" id="link" value="<?= $newsfeed->link ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="numarticles">
                            <?= @text( 'Number of Articles' ) ?>
                        </label>
                    </td>
                    <td>
                        <input class="inputbox required validate-integer validate-count" type="text" size="2" name="numarticles" id="numarticles" value="<?= $newsfeed->numarticles ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <span class="editlinktip hasTip" title="<?= @text( 'TIPCACHETIME' ) ?>">
                    <?= @text( 'Cache time' ) ?>
                </span>
                    </td>
                    <td>
                        <input class="inputbox required validate-integer validate-count" type="text" size="4" name="cache_time" id="cache_time" value="<?= $newsfeed->cache_time ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="ordering">
                            <?= @text( 'Ordering' ) ?>
                        </label>
                    </td>
                    <td>
                        <div id="orderable">
                             <? if( $newsfeed->id ) : ?>
                                <?= @helper('admin::com.categories.template.helper.listbox.order',
                                array( 'filter' => array(
                                    'parent' => 'com_newsfeeds'
                                )));
                            elseif ( substr($state->section, 0, 3) == 'com'):
                               echo @template('form_orderable');
                            endif  ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="key">
                        <label for="rtl">
                            <?= @text( 'RTL feed' ) ?>
                        </label>
                    </td>
                    <td>
                        <?= @helper('lib.koowa.template.helper.select.booleanlist', array('name' => 'rtl', 'selected' => $newsfeed->rtl)) ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                    </td>
                </tr>
                </table>
            </fieldset>
        </div>
        <div class="clr"></div>
    </form>
