<h2><?= _("Neue Gruppenmitglieder einladen") ?></h2>
<form action="<?= $controller->url_for('course/studygroup/edit_members/'.$sem_id.'/'.$GLOBALS['user']->id.'/add_invites/') ?>" method=post>
    <div>
        <?= _("Geben Sie zur Suche den Vor-, Nach- oder Usernamen ein.") ?><br>
        <?= QuickSearch::get("choose_member", $inviting_search)
                            ->withButton()
                            ->render() ?>
        <input type="image" name="add_member" <?= makebutton('einladen','src')?> style="vertical-align:middle;"><br>
    </div>
</form>
