<?
# Lifter010: TODO
?>
<?
    $help_url = format_help_url("Basis.VeranstaltungenVerwaltenGruppen");
?>
<br>
<p class="info">
    <?= $this->render_partial('statusgruppen/sem_edit_role.php', array('no_breaks' => true)) ?>
</p>

<p class="info">
    <?= _("Es sind noch keine Gruppen oder Funktionen angelegt worden.") ?><br>
    <?= _("Um f�r diesen Bereich Gruppen oder Funktionen anzulegen, nutzen Sie bitte die obere Zeile!") ?><br>
    <br>
    <?= _("Mit dem Feld 'Gruppengr��e' haben Sie die M�glichkeit, die Sollst�rke f�r eine Gruppe festzulegen. Dieser Wert ist nur aus Teilnehmersicht relevant - verantwortliche Personen (Tutoren, Lehrende) k�nnen auch mehr Gruppenmitglieder eintragen.") ?><br>
    <?= _("Wenn Sie Gruppen angelegt haben, k�nnen Sie diesen Personen zuordnen. Jeder Gruppe k�nnen beliebig viele Personen zugeordnet werden. Jede Person kann beliebig vielen Gruppen zugeordnet werden.") ?><br>
    <br>
    <?= sprintf(_("Lesen Sie weitere Bedienungshinweise in der %sHilfe%s nach!"), '<a href="' . $help_url . '" target="_blank">', '</a>') ?>
</p>
