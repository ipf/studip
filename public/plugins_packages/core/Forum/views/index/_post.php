<? if (!is_array($highlight)) $highlight = array(); ?>
<? $is_new =  ((isset($visitdate) && $post['mkdate'] >= $visitdate) || !(isset($visitdate))) ?>
<!-- Anker, um zu diesem Posting springen zu k�nnen -->
<a name="<?= $post['topic_id'] ?>"></a>

<form method="post" data-topicid="<?= $post['topic_id'] ?>">
    <?= CSRFProtection::tokenTag() ?>
    
<div class="posting <?=($zebra) ? 'bg1' : 'bg2'?>" style="position: relative;">
    <span class="corners-top"><span></span></span>

    <? if ($post['fav']) : ?>
    <div class="marked"></div>
    <? endif ?>

    <div class="postbody">
        <div class="title">

            <? if ($is_new && trim(ForumEntry::killFormat($post['name']))): ?>
            <span class="new_posting">
                <?= Assets::img('icons/16/red/new/forum.png', array(
                    'title' => _("Dieser Beitrag ist seit Ihrem letzten Besuch hinzugekommen.")
                )) ?>
            </span>
            <? endif ?>

            <span data-edit-topic="<?= $post['topic_id'] ?>" style="display: none">
                <input type="text" name="name" value="<?= htmlReady($post['name_raw']) ?>" data-reset="<?= htmlReady($post['name_raw']) ?>" style="width: 100%">
            </span>
            
            <span data-show-topic="<?= $post['topic_id'] ?>">
                <a href="<?= PluginEngine::getLink('coreforum/index/index/' . $post['topic_id'] .'?'. http_build_query(array('highlight' => $highlight)) ) ?>#<?= $post['topic_id'] ?>">
                <? if ($show_full_path) : ?>
                    <?= ForumHelpers::highlight(ForumEntry::killFormat(implode(' &gt;&gt; ', ForumEntry::getFlatPathToPosting($post['topic_id']))), $highlight) ?>
                <? else : ?>
                <span data-topic-name="<?= $post['topic_id'] ?>">
                    <?= ($post['name']) ? ForumHelpers::highlight($post['name'], $highlight) : ''?>
                </span>
                <? endif ?>
                </a>
            </span>

            <p class="author">
                <? if ($is_new && !trim(ForumEntry::killFormat($post['name']))): ?>
                    <?= Assets::img('icons/16/red/new/forum.png', array(
                        'title' => _("Dieser Beitrag ist seit Ihrem letzten Besuch hinzugekommen.")
                    )) ?>
                <? endif ?>
                von <strong><a href="<?= URLHelper::getLink('about.php?username='. get_username($post['owner_id'])) ?>">
                    <?= ForumHelpers::highlight(htmlReady($post['author']), $highlight) ?>
                </a></strong>
                am <?= strftime($time_format_string, (int)$post['mkdate']) ?>
            </p>
        </div>

        <!-- Aktionsicons -->
        <span class="action-icons likes" id="like_<?= $post['topic_id'] ?>">
            <?= $this->render_partial('index/_like', array('topic_id' => $post['topic_id'])) ?>
        </span>

        <!-- Postinginhalt -->
        <div class="content">
            <span data-edit-topic="<?= $post['topic_id'] ?>" style="display: none">
                <textarea data-textarea="<?= $post['topic_id'] ?>" data-reset="<?= htmlReady($post['content_raw']) ?>" name="content" class="add_toolbar"><?= htmlReady($post['content_raw']) ?></textarea>
            </span>
            
            <span data-show-topic="<?= $post['topic_id'] ?>" data-topic-content="<?= $post['topic_id'] ?>">
                <?= ForumHelpers::highlight($post['content'], $highlight) ?>
            </span>
        </div>
    </div>

    <? if (ForumPerm::hasEditPerms($post['topic_id'])) : ?>
    <span data-edit-topic="<?= $post['topic_id'] ?>" style="display: none">
        <dl class="postprofile">
            <dt>
                <?= $this->render_partial('index/_smiley_favorites', array('textarea_id' => $post['topic_id'])) ?>
            </dt>
        </dl>
    </span>
    <? endif ?>

    <!-- Infobox rechts neben jedem Posting -->
    <span data-show-topic="<?= $post['topic_id'] ?>">
        <dl class="postprofile">
            <dt>
                <a href="<?= URLHelper::getLink('about.php?username='. get_username($post['owner_id'])) ?>">
                    <?= Avatar::getAvatar($post['owner_id'])->getImageTag(Avatar::MEDIUM,
                        array('title' => get_username($post['owner_id']))) ?>
                    <br>
                    <span class="username" data-profile="<?= $post['topic_id'] ?>">
                        <?= htmlReady(get_fullname($post['owner_id'])) ?>
                    </span>
                </a>
            </dt>
            <dd>
                <?= ForumHelpers::translate_perm($GLOBALS['perm']->get_studip_perm($constraint['seminar_id'], $post['owner_id']))?>
            </dd>
            <dd class="online-status">
                <? switch(ForumHelpers::getOnlineStatus($post['owner_id'])) :
                    case 'available': ?>
                        <img src="<?= $picturepath ?>/community.png">
                        <?= _('Online') ?>
                    <? break; ?>

                    <? case 'away': ?>
                        <?= Assets::img('icons/16/grey/community.png') ?>
                        <?= _('Abwesend') ?>                        
                    <? break; ?>

                    <? case 'offline': ?>
                        <?= Assets::img('icons/16/black/community.png') ?>
                        <?= _('Offline') ?>
                    <? break; ?>
                <? endswitch ?>
            </dd>
            <dd>
                Beitr�ge:
                <?= ForumEntry::countUserEntries($post['owner_id']) ?>
            </dd>
            <? foreach (PluginEngine::sendMessage('PostingApplet', 'getHTML', $post['name_raw'], $post['content_raw'],
                    PluginEngine::getLink('coreforum/index/index/' . $post['topic_id'] .'#'. $post['topic_id']),
                    $post['owner_id']) as $applet_data) : ?>
            <dd>
                <?= $applet_data ?>
            </dd>
            <? endforeach ?>
        </dl>
    </span>

    <!-- Buttons for this Posting -->
    <div class="buttons">
        <div class="button-group">
    <? if (ForumPerm::hasEditPerms($post['topic_id'])) : ?>
    <span data-edit-topic="<?= $post['topic_id'] ?>" style="display: none">
        <!-- Buttons f�r den Bearbeitungsmodus -->
        <?= Studip\LinkButton::createAccept('�nderungen speichern', "javascript:STUDIP.Forum.saveEntry('". $post['topic_id'] ."')") ?>

        <?= Studip\LinkButton::createCancel('Abbrechen', "javascript:STUDIP.Forum.cancelEditEntry('". $post['topic_id'] ."')") ?>
        
        <?= Studip\LinkButton::create('Vorschau', "javascript:STUDIP.Forum.preview('". $post['topic_id'] ."', 'preview_". $post['topic_id'] ."');") ?>
    </span>
    <? endif ?>
            
    <span data-show-topic="<?= $post['topic_id'] ?>">
        <!-- Aktions-Buttons f�r diesen Beitrag -->
            
        <? if ($section == 'index' && ForumPerm::has('add_entry', $seminar_id)) : ?>
        <?= Studip\LinkButton::create('Beitrag zitieren', "javascript:STUDIP.Forum.citeEntry('". $post['topic_id'] ."')") ?>
        <? endif ?>

        <? if ($section == 'index' && ForumPerm::hasEditPerms($post['topic_id'])) : ?>
            <?= Studip\LinkButton::create('Beitrag bearbeiten', "javascript:STUDIP.Forum.editEntry('". $post['topic_id'] ."')") ?>
        <? endif ?>

        <? if ($section == 'index' && (ForumPerm::hasEditPerms($post['topic_id']) || ForumPerm::has('remove_entry', $seminar_id))) : ?>
            <? if ($constraint['depth'] == $post['depth']) : /* this is not only a posting, but a thread */ ?>
                <?= Studip\LinkButton::create('Thema l�schen', PluginEngine::getURL('coreforum/index/delete_entry/' . $post['topic_id']),
                    array('onClick' => "return confirm('". _('Wenn Sie diesen Beitrag l�schen wird ebenfalls das gesamte Thema gel�scht.\n'
                            . ' Sind Sie sicher, dass Sie das tun m�chten?') ."')")) ?>
            <? else : ?>
                <?= Studip\LinkButton::create('Beitrag l�schen', PluginEngine::getURL('coreforum/index/delete_entry/' . $post['topic_id']),
                    array('onClick' => "return confirm('". _('M�chten Sie diesen Beitrag wirklich l�schen?') ."')")) ?>
            <? /* "javascript:STUDIP.Forum.deleteEntry('{$post['topic_id']}');" */ ?>
            <? endif ?>
        <? endif ?>

        <? if (ForumPerm::has('fav_entry', $seminar_id)) : ?>
            <? if (!$post['fav']) : ?>
                <?= Studip\LinkButton::create('Beitrag merken', PluginEngine::getURL('coreforum/index/set_favorite/' . $post['topic_id'])) ?>
            <? else : ?>
                <?= Studip\LinkButton::create('Beitrag vernachl�ssigen', PluginEngine::getURL('coreforum/index/unset_favorite/' . $post['topic_id'])) ?>
            <? endif ?>
        <? endif ?>
        
        <? if (ForumPerm::has('forward_entry', $seminar_id)) : ?>
        <?= Studip\LinkButton::create('Beitrag weiterleiten', "javascript:STUDIP.Forum.forwardEntry('". $post['topic_id'] ."')") ?>
        <? endif ?>
    </span>
        </div>
    </div>

  <span class="corners-bottom"><span></span></span>
</div>
</form>

<?= $this->render_partial('index/_preview', array('preview_id' => 'preview_' . $post['topic_id'])) ?>