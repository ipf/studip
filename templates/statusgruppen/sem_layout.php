<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td class="blank" style="padding-left: 8px;" valign="top" width="80%">
            <?= $this->render_partial('shared/message_list', array('messages' => $messages)); ?>
            <?= $content_for_layout ?>
        </td>
        <td class="blank">&nbsp;</td>
        <td class="blank" width="240" valign="top">
            <?= $this->render_partial('statusgruppen/sem_infobox.php') ?>
        </td>
    </tr>
    <tr>
        <td class="blank" colspan="3">&nbsp;</td>
    </tr>
<table>
