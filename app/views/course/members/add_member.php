<? use Studip\Button, Studip\LinkButton?>

<h1><?=sprintf(_('%s hinzufügen'), htmlReady($decoratedStatusGroups['autor']))?></h1>
<?= CSRFProtection::tokenTag() ?>
<form action="<?=$controller->url_for('course/members/set_autor')?>" method="post">
    <input type="hidden" name="studipticket" value="<?=$studipticket?>">
    <table class="default zebra collapsable">
        <tbody>
            <tr class="table_header header-row">
                <th class="toggle-indicator" colspan="4">
                    <a class="toggler">
                        <?=sprintf(_('%s in die Veranstaltung hinzufügen'), htmlReady($decoratedStatusGroups['autor']))?>
                    </a>
                </th>
            </tr>
            <tr>
                <td style="width: 30%; text-align: left">
                    <?= sprintf(_('<strong>%s</strong> in die Veranstaltung eintragen'), htmlReady($decoratedStatusGroups['autor']))?>
                </td>
                <td style="width: 30%; text-align: left">
                    <?= QuickSearch::get("new_autor", $search)
                            ->withButton(array('reset_button_name' => 'reset_autor', 'search_button_name' => 'search_autor'))
                            ->render();
                    ?>
                    <input type="hidden" name="cid" value="<?= $course_id ?>">
                </td>
                <td style="width: 20%; text-align: center"> 
                    <? if ($semAdmissionEnabled)  :?>
                        <?= tooltipIcon(_('Hier können Sie auswählen, ob die von Ihnen hinzugefügten TeilnehmerInnen auf die Kontingentplätze angerechnet werden'))?>
                        
                        <label for="kontingent"><?=_("Kontingent berücksichtigen:");?>
                        <select name="consider_contingent" id="kontingent">
                            <option value=""><?=_("Kein Kontingent")?></option>
                            <? if(!empty($admission_studiengang)) :?>
                                <? foreach($admission_studiengang as $stg => $data) :?>
                            <option value="<?=$stg?>" <?=($stg == Request::get('consider_contingent')? 'selected="selected"' : '')?>><?= htmlReady($data['name'])?> - <?=  htmlReady($data['freeSeats'])?></option>
                                <? endforeach ?>
                            <? endif ?>
                        </select>
                        </label>
                    <? endif ?>
                </td>
                <td style="width: 20%; text-align: right">
                    <?= Button::createAccept(_('Eintragen'), 'add_autor', array('title' => sprintf(_("als %s eintragen"), $decoratedStatusGroups['autor']) )) ?>
                </td>
            </tr>
        </tbody>
    </table>
</form>

<?= CSRFProtection::tokenTag() ?>
<form action="<?= $controller->url_for('course/members/set_autor_csv')?>" method="post" name="user">
<input type="hidden" name="studipticket" value="<?=$studipticket?>">
<table class="default zebra collapsable">
    <tbody class="collapsed">
        <tr class="table_header header-row">
            <th class="toggle-indicator" colspan="3">
                <a class="toggler">
                    <?=_('Teilnehmerliste übernehmen')?>
                </a>
            </th>
        </tr>
        <tr>
            <td>
                <?=_('Eingabeformat')?>:
                
                <?= tooltipIcon(sprintf(_('In das Textfeld <strong>Teilnehmerliste übernehmen</strong> können Sie eine Liste mit Namen von %s eingeben, 
                    die in die Veranstaltung aufgenommen werden sollen. Wählen Sie in der Auswahlbox das gewünschte Format, in dem Sie die Namen eingeben möchten.<br />
                    <strong>Eingabeformat</strong><br/>
                    <strong>Nachname, Vorname &crarr;</strong><br />Geben Sie dazu in jede Zeile den Nachnamen und (optional) den Vornamen getrennt durch ein Komma oder ein Tabulatorzeichen ein.<br />
                    <strong>Nutzername &crarr;</strong><br />Geben Sie dazu in jede Zeile den Stud.IP Nutzernamen ein.'),$status_groups['autor']),false, true);?>
            </td>
            <td colspan="2">
                <select name="csv_import_format">
                    <option value="realname"><?=_("Nachname, Vorname")?> &crarr;</option>
                    <option value="username"><?=_("Nutzername")?> &crarr;</option>
                    <? if(!empty($accessible_df)) : ?>
                        <? foreach ($accessible_df as $df) : ?>
                            <option value="<?=$df->getId()?>" <?=(Request::get('csv_import_format') ==  $df->getId()? 'selected="selected"': '')?>><?= htmlReady($df->getName())?> &crarr;</option>
                        <? endforeach?>
                    <? endif ?>
                </select>
            </td>
        </tr>
        <? if ($semAdmissionEnabled)  :?>
        <tr>
            <td>
                <?=Assets::img('icons/16/black/info.png', 
                        array('title' => _('Mit dieser Einstellung beeinflussen Sie, ob Teilnehmer die Sie hinzufügen auf die Kontingentplätze angerechnet werden.'), 
                            'alt' => _('Kontingent berücksichtigen'),
                            'style' => 'cursor: pointer',
                            'onclick' => "alert('" . _('Mit dieser Einstellung beeinflussen Sie, ob Teilnehmer die Sie hinzufügen auf die Kontingentplätze angerechnet werden.') ." ')"))?>
                <?=_("Kontingent berücksichtigen:");?>
            </td>
            <td colspan="2">  
                <select name="consider_contingent_csv" id="kontingent_csv">
                    <option value=""><?=_("Kein Kontingent")?></option>
                    <? if(!empty($admission_studiengang)) :?>
                        <? foreach($admission_studiengang as $stg => $data) :?>
                    <option value="<?=$stg?>" <?=($stg == Request::get('consider_contingent_csv')? 'selected="selected"' : '')?>><?= htmlReady($data['name'])?> - <?=  htmlReady($data['freeSeats'])?></option>
                        <? endforeach ?>
                    <? endif ?>
                </select>
            </td>
        </tr>
        <? endif ?>
        <tr>
            <td style="width: 30%"><?= sprintf(_('<strong>%s</strong> in die Veranstaltung eintragen'), htmlReady($decoratedStatusGroups['autor']))?></td>
            <td style="width: 50%">
                <textarea name="csv_import" rows="6" cols="50"></textarea>
            </td>
            <td style="width: 20%; text-align: right">
                <?= Button::createAccept(_('Eintragen'), 'add_member_list', 
                        array('title' => sprintf(_("als %s eintragen"), htmlReady($decoratedStatusGroups['autor'])))) ?>
            </td>
        </tr>
    </tbody>
</table>
</form>

<div style="text-align: right">
    <?= LinkButton::createCancel(_('Abbrechen'), $controller->url_for('course/members/index')) ?>
</div>
