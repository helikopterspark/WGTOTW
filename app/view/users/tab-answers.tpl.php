<div class="tab-section">
    <div class='tab-button'><a href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=comments")?>' title='Kommentarer'>
        <i class="fa fa-comments"></i> <?=$cCount?> <?php $word = $cCount == 1 ? 'Kommentar' : 'Kommentarer'; echo $word; ?>
    </a></div>
    <div class='tab-button-selected'><a href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=answers")?>' title='Svar'>
        <i class="fa fa-exclamation"></i> <?=$aCount?> Svar
    </a></div>
    <div class='tab-button'><a href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=questions")?>' title='Frågor'>
        <i class="fa fa-question"></i> <?=$qCount?> <?php $word = $qCount == 1 ? 'Fråga' : 'Frågor'; echo $word; ?>
    </a></div>
</div> <!-- tab-section -->

<?php if ($aCount > 0): ?>
    <table class="userdetails-tab-table">
        <thead>
            <tr>
                <td>Fråga</td>
                <td class="center-align">Accepterat svar</td>
                <td class="center-align">Rank</td>
                <td class="right-align">Datum tid</td>
            </tr>
        </thead>
        <?php foreach ($content as $answer): ?>
            <tr>
                <td class="userdetails-tab-table-td"><a href='<?=$this->url->create('question/id').'/'.$answer->getProperties()['questionId'].'#answer-'.$answer->getProperties()['id']?>'>
                    <?=mb_substr($answer->getProperties()['qtitle'], 0, 64)?></a></td>
                    <td class="center-align"><?php if ($answer->getProperties()['accepted']): ?>
                        <span class="answer-accepted"><i class="fa fa-check"></i></span>
                    <?php endif; ?></td>
                    <td class="center-align"><?=$answer->getProperties()['upvotes'] - $answer->getProperties()['downvotes']?></td>
                    <td class="right-align"><?=$answer->getProperties()['created']?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <hr>
    <div class="users-overview-container">
        <a class='answer-button' href='<?=$this->url->create('users')?>'>ÖVERSIKT</a>
    </div>
</article>
