<p>
    <a class='edit-button' href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=questions")?>' title='Frågor'>
        <span class='lowered-letter'><i class="fa fa-question fa-2x"></i></span> <?=$qCount?> <?php $word = $qCount == 1 ? 'Fråga' : 'Frågor'; echo $word; ?>
    </a>
    <a class='comment-button' href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=answers")?>' title='Svar'>
        <span class='lowered-letter'><i class="fa fa-exclamation fa-2x"></i></span> <?=$aCount?> Svar
    </a>
    <a class='comment-button' href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=comments")?>' title='Kommentarer'>
        <span class='lowered-letter'><i class="fa fa-comments fa-2x"></i></span> <?=$cCount?> <?php $word = $cCount == 1 ? 'Kommentar' : 'Kommentarer'; echo $word; ?>
    </a>
</p>

<h4><?=$qCount?> <?php $word = $qCount == 1 ? 'Fråga' : 'Frågor'; echo $word; ?></h4>
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
