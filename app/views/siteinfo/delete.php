<div class="white" style="padding: 1ex;">

  <? if (isset($error_msg)): ?>
    <table style="width: 100%;">
      <? my_error($error_msg, '', 1, false, true) ?>
    </table>
  <? else: ?>
   <? if (!$execute): ?>     
    <div class="effect_highlight" style="text-align: center;padding: 10px;">
        <? if ($detail){
                $question = _("Wollen Sie die Seite wirklich l�schen?");
            }else{
                $question = _("Wollen Sie die Rubrik mit allen Seiten wirklich l�schen?")
            } ?>
      <p><?= $question ?></p>
        <? $delete_url = 'siteinfo/delete/'.$currentrubric.'/';
           $delete_url .= $detail ? $currentdetail : "all";
           $delete_url .= "/execute";
           $abort_url = 'siteinfo/show/'.$currentrubric;
           $abort_url .= $detail ? "/".$currentdetail : '';
        ?>
        <a href="<?= $controller->url_for($delete_url) ?>">
                 <?= makeButton("loeschen", "img") ?> 
        </a>
        <a href="<?= $controller->url_for($abort_url) ?>">
                 <?= makeButton("abbrechen", "img") ?>
        </a>
    </div>
   <? endif ?>
  <?= $output ?>
  <? endif ?>
</div>
