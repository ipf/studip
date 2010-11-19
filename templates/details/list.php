<b><?= $title ?>:</b><br>
<? if (sizeof($data) == 1) : ?>
    <a href="<?= URLHelper::getLink($data[0]['link']) ?>">
        <?= htmlReady($data[0]['name']) ?>
    </a>
<? else : ?>
    <ul class="default">
    <? foreach ($data as $element) : ?>
        <li>
            <a href="<?= URLHelper::getLink($element['link']) ?>">
                <?= htmlReady($element['name']) ?>
            </a>
        </li>
    <? endforeach ?>
    </ul>
<? endif ?>
