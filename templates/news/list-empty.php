<table id="news_box" role="article" class="index_box" <? if ($width): ?> style="width: <?= $width ?>;"<? endif; ?>>
    <tr>
        <td class="topic">
            <img src="<?= Assets::image_path('icons/16/white/breaking-news.png') ?>"
                 <?= tooltip(_('Newsticker. Klicken Sie rechts auf die Zahnr�der, '
                              .'um neue News in diesen Bereich einzustellen. Klicken '
                              .'Sie auf die Pfeile am linken Rand, um den ganzen '
                              .'Nachrichtentext zu lesen.')) ?>>
            <b><?= _('Ank�ndigungen') ?></b>
        </td>
        <td align="right" class="topic">
            <a href="<?= URLHelper::getLink('admin_news.php?' . $admin_link . '&cmd=new_entry') ?>">
                <img src="<?= Assets::image_path('icons/16/white/admin.png') ?>"
                     <?= tooltip(_('Ank�ndigungen einstellen')) ?>>
            </a>
        </td>
    </tr>
    <tr>
        <td class="steel1" colspan="2">
            <p class="info">
                <?= _('Es sind keine aktuellen Ank�ndigungen vorhanden. '
                     .'Um neue Ank�ndigungen zu erstellen, klicken Sie rechts auf die Zahnr�der.') ?>
            </p>
        </td>
    </tr>
</table>