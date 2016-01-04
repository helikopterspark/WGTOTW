<div id='comment-<?=$comment->getProperties()['id']?>' class='comment-container'>
    <img src='<?=$comment->user->getProperties()['gravatar']?>' alt='Gravatar'>
    <div class="comment-section">
        <p><span class='comments-name'><a href='<?=$this->url->create('users/id').'/'.$comment->user->getProperties()['id']?>'><?=$comment->user->getProperties()['name']?></a></span>
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

                <?php if ($comment->user->getProperties()['url']) : ?>
                    <br/>
                    <a href='<?=$comment->user->getProperties()['url']?>'><?=$comment->user->getProperties()['url']?></a>
                <?php endif; ?>
            </span>
        </p>
        <p><?=$comment->getProperties()['content']?></p>
        <?php if ($this->di->UserloginController->checkLoginCorrectUser($comment->user->getProperties()['id'])): ?>
            <p><a class='edit-button' href='<?=$this->url->create("{$this->request->getRoute()}?editcomment=yes&amp;commentid=".$comment->getProperties()['id']."#comment-editform-container")?>' title='Redigera'><i class="fa fa-pencil"></i> Redigera</a></p>
        <?php endif; ?>
        <p>Rank&nbsp;<?=$comment->getProperties()['upvotes'] - $comment->getProperties()['downvotes']?>
            <?php if (!$vote): ?>
                &nbsp;<a class='upvote-active' href='<?=$this->url->create("comments/upvote/".$comment->getProperties()['id'].'?qid='.$qid)?>' title='Bra kommentar'><i class="fa fa-thumbs-o-up"></i>&nbsp;<?=$comment->getProperties()['upvotes']?></a>
                &nbsp;<a class='downvote-active' href='<?=$this->url->create("comments/downvote/".$comment->getProperties()['id'].'?qid='.$qid)?>' title='Mindre bra kommentar'><i class="fa fa-thumbs-o-down"></i>&nbsp;<?=$comment->getProperties()['downvotes']?></a>
            <?php else : ?>
                &nbsp;<span class='upvote'><i class="fa fa-thumbs-o-up"></i></span>&nbsp;<?=$comment->getProperties()['upvotes']?>
                &nbsp;<span class='downvote'><i class="fa fa-thumbs-o-down"></i></span>&nbsp;<?=$comment->getProperties()['downvotes']?>
            <?php endif; ?>
        </p>
    </div> <!-- comment-section -->
</div> <!-- comment-container -->
