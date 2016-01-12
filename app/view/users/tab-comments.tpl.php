<div class="tab-section">
    <div class='tab-button-selected'><a href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=comments")?>' title='Kommentarer'>
        <i class="fa fa-comments"></i> <?=$cCount?> <?php $word = $cCount == 1 ? 'Kommentar' : 'Kommentarer'; echo $word; ?>
    </a></div>
    <div class='tab-button'><a href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=answers")?>' title='Svar'>
        <i class="fa fa-exclamation"></i> <?=$aCount?> Svar
    </a></div>
    <div class='tab-button'><a href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=questions")?>' title='Frågor'>
        <i class="fa fa-question"></i> <?=$qCount?> <?php $word = $qCount == 1 ? 'Fråga' : 'Frågor'; echo $word; ?>
    </a></div>
</div>

<?php if (count($content['questioncomments']) > 0): ?>
    <table class="userdetails-tab-table">
        <thead>
            <tr>
                <td>Kommenterade frågor</td>
                <td class="center-align">Rank</td>
                <td class="right-align">Datum tid</td>
            </tr>
        </thead>
        <?php foreach ($content['questioncomments'] as $comment): ?>
            <tr>
                <td class="userdetails-tab-table-td"><a href='<?=$this->url->create('question/id').'/'.$comment->getProperties()['qID'].'#comment-'.$comment->getProperties()['id']?>'>
                    <?=mb_substr($comment->getProperties()['qtitle'], 0, 64)?> ...</a></td>
                    <td class="center-align"><?=$comment->getProperties()['upvotes'] - $comment->getProperties()['downvotes']?></td>
                    <td class="right-align"><?=$comment->getProperties()['created']?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <?php if (count($content['answercomments']) > 0): ?>
        <table class="userdetails-tab-table">
            <thead>
                <tr>
                    <td>Kommenterade svar</td>
                    <td class="center-align">Rank</td>
                    <td class="right-align">Datum tid</td>
                </tr>
            </thead>
            <?php foreach ($content['answercomments'] as $comment): ?>
                <tr>
                    <td class="userdetails-tab-table-td"><a href='<?=$this->url->create('question/id').'/'.$comment->getProperties()['qID'].'#comment-'.$comment->getProperties()['id']?>'>
                        <?=mb_substr($comment->filteredcontent, 0, 64)?> ...</a></td>
                        <td class="center-align"><?=$comment->getProperties()['upvotes'] - $comment->getProperties()['downvotes']?></td>
                        <td class="right-align"><?=$comment->getProperties()['created']?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <hr>
        <div class="users-overview-container">
            <a class='answer-button' href='<?=$this->url->create('users')?>'>ÖVERSIKT</a>
        </div>
    </article>
