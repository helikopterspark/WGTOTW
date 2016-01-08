<div id='comment-<?=$comment->getProperties()['id']?>' class='comment-container'>
    <!--
    <div class="comment-detail-stats">
        <p>
            <?php if (!$vote): ?>
                <a class='upvote-active' href='<?=$this->url->create("comments/upvote/".$comment->getProperties()['id'].'?qid='.$qid)?>' title='Bra kommentar'><i class="fa fa-caret-up fa-2x"></i></a><br>
                <span class="smaller-text"><?=$comment->getProperties()['upvotes'] - $comment->getProperties()['downvotes']?><br>
                <a class='downvote-active' href='<?=$this->url->create("comments/downvote/".$comment->getProperties()['id'].'?qid='.$qid)?>' title='Mindre bra kommentar'><i class="fa fa-caret-down fa-2x"></i></a></span>
                <?php else : ?>
                    <span class='upvote'><i class="fa fa-caret-up fa-2x"></i></span><br>
                    <span class="smaller-text"><?=$comment->getProperties()['upvotes'] - $comment->getProperties()['downvotes']?></span><br>
                    <span class='downvote'><i class="fa fa-caret-down fa-2x"></i></span>
                <?php endif; ?>
            </p>
        </div> --> <!-- question-detail-stats -->

    <div class="comment-section">
        <?=$comment->filteredcontent?>
        <div class="comment-userinfo">
        <p><span class='comments-name'><a href='<?=$this->url->create('users/id').'/'.$comment->user->getProperties()['id']?>'><?=$comment->user->getProperties()['acronym']?></a></span>
            <span class='comments-id-time'>|
                <?php $timestamp = strtotime($comment->getProperties()['created']); ?>

                <?php $timeinterval = time() - $timestamp; ?>
                <?php if (($timeinterval) < 60): ?>
                    <?=round($timeinterval)?> sekunder sedan
                <?php elseif (($timeinterval/60) < 1.5): ?>
                    <?=round($timeinterval/60)?> minut sedan
                <?php elseif (($timeinterval/60) < 60): ?>
                    <?=round($timeinterval/60)?> minuter sedan
                <?php elseif (($timeinterval/(60*60)) < 1.5): ?>
                    <?=round($timeinterval/(60*60))?> timme sedan
                <?php elseif (($timeinterval/(60*60)) < 24): ?>
                    <?=round($timeinterval/(60*60))?> timmar sedan
                <?php elseif (($timeinterval/(60*60*24)) < 7): ?>
                    <?=round($timeinterval/(60*60*24))?> dygn sedan
                <?php elseif (($timeinterval/(60*60*24)) < 10.5) : ?>
                    <?=round($timeinterval/(60*60*24*7))?> vecka sedan
                <?php elseif (($timeinterval/(60*60*24)) < 30) : ?>
                    <?=round($timeinterval/(60*60*24*7))?> veckor sedan
                <?php elseif (($timeinterval/(60*60*24)) < 45) : ?>
                    <?=round($timeinterval/(60*60*24*7))?> månad sedan
                <?php else : ?>
                    <?=round($timeinterval/(60*60*24*30))?> månader sedan
                <?php endif; ?>

                <?php if (isset($comment->getProperties()['updated'])) : ?>
                    | <span class='italics'> Uppdaterad för
                    <?php $timestamp = strtotime($comment->getProperties()['updated']); ?>

                    <?php $timeinterval = time() - $timestamp; ?>
                    <?php if (($timeinterval) < 60): ?>
                        <?=round($timeinterval)?> sekunder sedan
                    <?php elseif (($timeinterval/60) < 1.5): ?>
                        <?=round($timeinterval/60)?> minut sedan
                    <?php elseif (($timeinterval/60) < 60): ?>
                        <?=round($timeinterval/60)?> minuter sedan
                    <?php elseif (($timeinterval/(60*60)) < 1.5): ?>
                        <?=round($timeinterval/(60*60))?> timme sedan
                    <?php elseif (($timeinterval/(60*60)) < 24): ?>
                        <?=round($timeinterval/(60*60))?> timmar sedan
                    <?php elseif (($timeinterval/(60*60*24)) < 7): ?>
                        <?=round($timeinterval/(60*60*24))?> dygn sedan
                    <?php elseif (($timeinterval/(60*60*24)) < 10.5) : ?>
                        <?=round($timeinterval/(60*60*24*7))?> vecka sedan
                    <?php elseif (($timeinterval/(60*60*24)) < 30) : ?>
                        <?=round($timeinterval/(60*60*24*7))?> veckor sedan
                    <?php elseif (($timeinterval/(60*60*24)) < 45) : ?>
                        <?=round($timeinterval/(60*60*24*7))?> månad sedan
                    <?php else : ?>
                        <?=round($timeinterval/(60*60*24*30))?> månader sedan
                    <?php endif; ?>
                    </span>
                <?php endif; ?>

                <?php if (!$vote): ?>
                    &nbsp;<a class='upvote-active lowered-letter' href='<?=$this->url->create("comments/upvote/".$comment->getProperties()['id'].'?qid='.$qid)?>' title='Bra kommentar'><i class="fa fa-caret-up fa-2x"></i></a>
                    &nbsp;<span class="smaller-text"><?=$comment->getProperties()['upvotes'] - $comment->getProperties()['downvotes']?></span>
                    &nbsp;<a class='downvote-active lowered-letter' href='<?=$this->url->create("comments/downvote/".$comment->getProperties()['id'].'?qid='.$qid)?>' title='Mindre bra kommentar'><i class="fa fa-caret-down fa-2x"></i></a>
                    <?php else : ?>
                        &nbsp;<span class='upvote lowered-letter'><i class="fa fa-caret-up fa-2x"></i></span>
                        &nbsp;<span class="smaller-text"><?=$comment->getProperties()['upvotes'] - $comment->getProperties()['downvotes']?></span>
                        &nbsp;<span class='downvote lowered-letter'><i class="fa fa-caret-down fa-2x"></i></span>
                    <?php endif; ?>
            </span>
            <?php if ($this->di->UserloginController->checkLoginCorrectUser($comment->user->getProperties()['id'])): ?>
                &nbsp;<a class='edit-button' href='<?=$this->url->create("{$this->request->getRoute()}?editcomment=yes&amp;commentid=".$comment->getProperties()['id']."#comment-editform-container")?>' title='Redigera'><i class="fa fa-pencil"></i> Redigera</a>
            <?php endif; ?>
        </p>
    </div>
    </div> <!-- comment-section -->
</div> <!-- comment-container -->
