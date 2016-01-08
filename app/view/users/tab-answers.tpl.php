<div class="tab-section">
    <h4><?=$aCount?> Svar</h4>
<p class='button-right'>
    <a class='tab-button' href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=questions")?>' title='Frågor'>
        <span class='lowered-letter'><i class="fa fa-question fa-2x"></i></span> <?=$qCount?> <?php $word = $qCount == 1 ? 'Fråga' : 'Frågor'; echo $word; ?>
    </a>
    <a class='tab-button-selected' href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=answers")?>' title='Svar'>
        <span class='lowered-letter'><i class="fa fa-exclamation fa-2x"></i></span> <?=$aCount?> Svar
    </a>
    <a class='tab-button' href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=comments")?>' title='Kommentarer'>
        <span class='lowered-letter'><i class="fa fa-comments fa-2x"></i></span> <?=$cCount?> <?php $word = $cCount == 1 ? 'Kommentar' : 'Kommentarer'; echo $word; ?>
    </a>
</p>
</div> <!-- tab-section -->

<?php if ($aCount > 0): ?>
    <table>
        <thead>
            <tr>
                <td>Fråga</td>
                <td>Accepterat svar</td>
                <td>Rank</td>
                <td class="right-align">Datum tid</td>
            </tr>
        </thead>
        <?php foreach ($content as $answer): ?>
            <tr>
                <td><a href='<?=$this->url->create('question/id').'/'.$answer->getProperties()['questionId'].'#answer-'.$answer->getProperties()['id']?>'><?=$answer->getProperties()['qtitle']?></a></td>
                <td><?php if ($answer->getProperties()['accepted']): ?>
                    <span class="answer-accepted"><i class="fa fa-check"></i></span>
                <?php endif; ?></td>
                <td><?=$answer->getProperties()['upvotes'] - $answer->getProperties()['downvotes']?></td>
                <td class="right-align"><?=$answer->getProperties()['created']?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<p><a href='<?=$this->url->create('users')?>'>Översikt</a></p>
</article>
