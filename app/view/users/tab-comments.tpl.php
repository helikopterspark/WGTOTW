<p>
    <a class='comment-button' href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=questions")?>' title='Frågor'>
        <span class='lowered-letter'><i class="fa fa-question fa-2x"></i></span> <?=$qCount?> <?php $word = $qCount == 1 ? 'Fråga' : 'Frågor'; echo $word; ?>
    </a>
    <a class='comment-button' href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=answers")?>' title='Svar'>
        <span class='lowered-letter'><i class="fa fa-exclamation fa-2x"></i></span> <?=$aCount?> Svar
    </a>
    <a class='edit-button' href='<?=$this->url->create("users/id/".$user->getProperties()['id']."?tab=comments")?>' title='Kommentarer'>
        <span class='lowered-letter'><i class="fa fa-comments fa-2x"></i></span> <?=$cCount?> <?php $word = $cCount == 1 ? 'Kommentar' : 'Kommentarer'; echo $word; ?>
    </a>
</p>

<h4><?=$cCount?> <?php $word = $cCount == 1 ? 'Kommentar' : 'Kommentarer'; echo $word; ?></h4>
<?php if (count($content['questioncomments']) > 0): ?>
<table>
    <thead>
        <tr>
            <td>Kommenterade frågor</td>
            <td>Rank</td>
            <td class="right-align">Datum tid</td>
        </tr>
    </thead>
    <?php foreach ($content['questioncomments'] as $comment): ?>
        <tr>
            <td><a href='<?=$this->url->create('question/id').'/'.$comment->getProperties()['qID'].'#comment-'.$comment->getProperties()['id']?>'><?=$comment->getProperties()['qtitle']?></a></td>
            <td><?=$comment->getProperties()['upvotes'] - $comment->getProperties()['downvotes']?></td>
            <td class="right-align"><?=$comment->getProperties()['created']?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
<?php if (count($content['answercomments']) > 0): ?>
<table>
    <thead>
        <tr>
            <td>Kommenterade svar</td>
            <td>Rank</td>
            <td class="right-align">Datum tid</td>
        </tr>
    </thead>
    <?php foreach ($content['answercomments'] as $comment): ?>
        <tr>
            <td><a href='<?=$this->url->create('question/id').'/'.$comment->getProperties()['qID'].'#comment-'.$comment->getProperties()['id']?>'><?=$comment->getProperties()['atitle']?></a></td>
            <td><?=$comment->getProperties()['upvotes'] - $comment->getProperties()['downvotes']?></td>
            <td class="right-align"><?=$comment->getProperties()['created']?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
<p><a href='<?=$this->url->create('users')?>'>Översikt</a></p>
</article>
