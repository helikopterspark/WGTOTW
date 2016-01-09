<div class="tab-section">
    <div class="tab-sidebar">
        <!-- <p class="smaller-text"><?=$qCount?> <?php $word = $qCount == 1 ? 'Fråga' : 'Frågor'; echo $word; ?></p> -->
    </div>
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
    <table>
        <thead>
            <tr>
                <td>Fråga</td>
                <td>Rank</td>
                <td>Antal svar</td>
                <td class="right-align">Datum tid</td>
            </tr>
        </thead>
        <?php foreach ($content as $question): ?>
            <tr>
                <td><a href='<?=$this->url->create('question/id').'/'.$question->getProperties()['id']?>'><?=$question->getProperties()['title']?></a><br>
                    <?php foreach ($question->tags as $tag) : ?>
                        <span class="tag-badge"><a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>' title='<?=$tag->getProperties()['description']?>'><?=$tag->getProperties()['name']?></a></span>
                    <?php endforeach; ?>
                </td>
                <td><?=$question->getProperties()['upvotes'] - $question->getProperties()['downvotes']?> (<?=$question->getProperties()['upvotes'] + $question->getProperties()['downvotes']?> röster)</td>
                <td><?=$question->noOfAnswers?></td>
                <td class="right-align"><?=$question->getProperties()['created']?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<p><a href='<?=$this->url->create('users')?>'>Översikt</a></p>
</article>
