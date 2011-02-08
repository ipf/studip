<? if ($noteditable) : ?>
<?= MessageBox::info(_("Das Startdatum kann nur bei Semestern ge�ndert werden, in denen keine Veranstaltungen liegen!")) ?>
<? endif ?>

<form method="post" action="<?= $controller->url_for('admin/semester/edit_semester') ?><?= ($semester['semester_id']) ? '/'.$semester['semester_id'] : '' ?>">
<?= CSRFProtection::tokenTag() ?>
<table class="default">
    <tr>
    <?if ($semester) : ?>
         <th colspan="5"><?= _("Semester bearbeiten") ?></th>
    <? else: ?>
         <th colspan="5"><?= _("Semester neu anlegen") ?></th>
    <? endif ?>
    </tr>
    <tr class="<?= TextHelper::cycle('steel1', 'steelgraulight') ?>">
        <td>
            <?= _("Name des Semesters:") ?>
        </td>
        <td colspan="4">
            <input type="text" size="60" value="<?= ($semester['name']) ? $semester['name'] : '' ?>" name="name" style="width: 350px;">
        </td>
    </tr>
    <tr class="<?= TextHelper::cycle('steel1', 'steelgraulight') ?>">
        <td>
            <?= _("Beschreibung:") ?>
        </td>
        <td colspan="4">
            <textarea name="description" rows="4" cols="50" style="width: 350px;"><?= ($semester['description']) ? $semester['description'] : '' ?></textarea>
        </td>
    </tr>
    <tr class="<?= TextHelper::cycle('steel1', 'steelgraulight') ?>">
        <td>
            <?= _("Semesterzeitraum:") ?>
        </td>
        <td>
            <?= _("Beginn:") ?>
        </td>
        <td>
            <input id="beginn" type="text" name="beginn" value="<?= ($semester['beginn']) ? date('d.m.Y', $semester['beginn']) : '' ?>"<?= ($noteditable) ? ' disabled="disabled"' : '' ?>>
        </td>
        <td>
            <?= _("Ende:") ?>
        </td>
        <td>
            <input id="ende" type="text" name="ende" value="<?= ($semester['ende']) ? date('d.m.Y', $semester['ende']) : '' ?>">
        </td>
    </tr>
    <tr class="<?= TextHelper::cycle('steel1', 'steelgraulight') ?>">
        <td>
            <?= _("Vorlesungszeitraum:") ?>
        </td>
        <td>
            <?= _("Beginn:") ?>
        </td>
        <td>
            <input id="vorles_beginn" type="text" name="vorles_beginn" value="<?= ($semester['vorles_beginn']) ? date('d.m.Y', $semester['vorles_beginn']) : '' ?>">
        </td>
        <td>
            <?= _("Ende:") ?>
        </td>
        <td>
            <input id="vorles_ende" type="text" name="vorles_ende" value="<?= ($semester['vorles_ende']) ? date('d.m.Y', $semester['vorles_ende']) : '' ?>">
        </td>
    </tr>
    <tr>
        <td colspan="5" align="center">
        <? if ($semester['semester_id']) : ?>
            <?= makeButton("speichern", 'input', _('Die �nderungen speichern')) ?>
        <? else : ?>
            <?= makeButton("anlegen", 'input', _('Neues Semester anlegen')) ?>
        <? endif ?>
            <a href="<?= $controller->url_for('admin/semester') ?>"><?= makeButton("abbrechen") ?></a>
        </td>
    </tr>
</table>
</form>

<script>
    jQuery('#beginn').datepicker();
    jQuery('#ende').datepicker();
    jQuery('#vorles_beginn').datepicker();
    jQuery('#vorles_ende').datepicker();
</script>