<div class="tab-section">
    <div class='tab-button'><a href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=comments")?>' title='Kommentarer'>
        <i class="fa fa-comments"></i> <?=$cCount?> <?php $word = $cCount == 1 ? 'Kommentar' : 'Kommentarer'; echo $word; ?>
    </a></div>
    <div class='tab-button'><a href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=answers")?>' title='Svar'>
        <i class="fa fa-exclamation"></i> <?=$aCount?> Svar
    </a></div>
    <div class='tab-button-selected'><a href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=questions")?>' title='Frågor'>
        <i class="fa fa-question"></i> <?=$qCount?> <?php $word = $qCount == 1 ? 'Fråga' : 'Frågor'; echo $word; ?>
    </a></div>
</div>

<?php if ($qCount > 0): ?>
    <table class="userdetails-tab-table">
        <thead>
            <tr>
                <td>Fråga</td>
                <td class="center-align">Rank</td>
                <td class="center-align">Antal svar</td>
                <td class="right-align">Datum tid</td>
            </tr>
        </thead>
        <?php foreach ($content as $question): ?>
            <tr>
                <td width="60%"><a href='<?=$this->url->create('question/id').'/'.$question->getProperties()['id']?>'>
                    <div class="question-tags-cell"><?=mb_substr($question->getProperties()['title'], 0, 64)?></a></div>
                    <div>
                        <?php foreach ($question->tags as $tag) : ?>
                            <span class="smaller-text"><span class="tag-badge"><a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>' title='<?=$tag->getProperties()['description']?>'>
                                <?=$tag->getProperties()['name']?></a></span></span>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td class="center-align"><?=$question->getProperties()['upvotes'] - $question->getProperties()['downvotes']?></td>
                    <td class="center-align"><?=$question->noOfAnswers?></td>
                    <td class="right-align"><?=$question->getProperties()['created']?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <hr>
    <div class="users-overview-container">
        <a class='answer-button' href='<?=$this->url->create('users')?>'>ÖVERSIKT</a>
    </div>
</article>
