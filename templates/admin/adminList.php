<form action="<? URLHelper::getLink("?", array('cid' => null)) ?>" method="get">
    <select name="cid" onKeyDown="if (event.keyCode === 13) { jQuery(this).closest('form')[0].submit(); }" onClick="jQuery(this).closest('form')[0].submit();" size="10" style="max-width: 200px;">
    <? foreach ($adminList as $seminar) : ?>
        <option title="<?= htmlReady($seminar['Name']) ?>" value="<?= htmlReady($seminar['Seminar_id']) ?>"<?= ($seminar['Seminar_id'] === $course_id ? " selected" : "") ?>><?= htmlReady($seminar['Name']) ?></option>
    <? endforeach ?>
    </select>
</form>