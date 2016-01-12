<!doctype html>
<html <?=$this->theme->getClassAttributeFor("html","no-js")?> lang='<?=$lang?>'>
<head>
<meta charset='utf-8' />
<meta name='viewport' content='width=device-width initial-scale=1' />
<title><?=$title . $title_append?></title>
<?php if(isset($favicon)): ?><link rel='icon' href='<?=$this->url->asset($favicon)?>'/><?php endif; ?>
<?php foreach($stylesheets as $stylesheet): ?>
<link rel='stylesheet' type='text/css' href='<?=$this->url->asset($stylesheet)?>'/>
<?php endforeach; ?>
<?php if(isset($style)): ?><style><?=$style?></style><?php endif; ?>
<script src='<?=$this->url->asset($modernizr)?>'></script>
</head>

<body>



<div id='wrapper' <?=$this->theme->getClassAttributeFor("wrapper")?>>

<div id='topheader'>
<?php if(isset($topheader)) echo $topheader?>
<?php $this->views->render('topheader')?>
</div>

<div id='header'>
<?php if(isset($header)) echo $header?>
<?php $this->views->render('header')?>
</div>

<?php if ($this->views->hasContent('navbar')) : ?>
<div id='navbar'>
<?php $this->views->render('navbar')?>
</div>
<?php endif; ?>

<?php if ($this->views->hasContent('flash')) : ?>
	<div id='flash'><?php $this->views->render('flash')?></div>
<?php endif; ?>

<?php if ($this->views->hasContent('featured-1', 'featured-2', 'featured-3')) : ?>
<div id='wrap-featured'>
	<div id='featured-1'><?php $this->views->render('featured-1')?></div>
	<div id='featured-2'><?php $this->views->render('featured-2')?></div>
	<div id='featured-3'><?php $this->views->render('featured-3')?></div>
</div>
<?php endif; ?>

<div id='wrap-main' <?=$this->theme->getClassAttributeFor("wrap-main")?>>

<?php if ($this->views->hasContent('main')) : ?>
	<div id='main' <?=$this->theme->getClassAttributeFor("main")?>><?php $this->views->render('main')?></div>
<?php endif; ?>

<?php if ($this->views->hasContent('sidebar')) : ?>
	<div id='sidebar' <?=$this->theme->getClassAttributeFor("sidebar")?>><?php $this->views->render('sidebar')?></div>
<?php endif; ?>

<?php if ($this->views->hasContent('main-extended')) : ?>
	<div id='main-extended' <?=$this->theme->getClassAttributeFor("main-extended")?>><?php $this->views->render('main-extended')?></div>
<?php endif; ?>

<?php if ($this->views->hasContent('sidebar-reduced')) : ?>
	<div id='sidebar-reduced' <?=$this->theme->getClassAttributeFor("sidebar-reduced")?>><?php $this->views->render('sidebar-reduced')?></div>
<?php endif; ?>

<?php if ($this->views->hasContent('fullpage')) : ?>
	<div id='fullpage' <?=$this->theme->getClassAttributeFor("fullpage")?>><?php $this->views->render('fullpage')?></div>
<?php endif; ?>
</div>

<?php if ($this->views->hasContent('triptych-1', 'triptych-2', 'triptych-3')) : ?>
<div id='wrap-triptych' <?=$this->theme->getClassAttributeFor("wrap-triptych")?>>
	<div id='triptych-1'><?php $this->views->render('triptych-1')?></div>
	<div id='triptych-2'><?php $this->views->render('triptych-2')?></div>
	<div id='triptych-3'><?php $this->views->render('triptych-3')?></div>
</div>
<?php endif; ?>

<?php if ($this->views->hasContent('footer-col-1', 'footer-col-2', 'footer-col-3', 'footer-col-4')) : ?>
<div id='wrap-footer-col' <?=$this->theme->getClassAttributeFor("wrap-footer-col")?>>
	<div id='footer-col-1'><?php $this->views->render('footer-col-1')?></div>
	<div id='footer-col-2'><?php $this->views->render('footer-col-2')?></div>
	<div id='footer-col-3'><?php $this->views->render('footer-col-3')?></div>
	<div id='footer-col-4'><?php $this->views->render('footer-col-4')?></div>
</div>
<?php endif; ?>
<!--
<div id='footer' <?=$this->theme->getClassAttributeFor("footer")?>>
<?php if(isset($footer)) echo $footer?>
<?php $this->views->render('footer')?>
</div>
-->
</div>

<div id='footer' <?=$this->theme->getClassAttributeFor("footer")?>>
<?php if(isset($footer)) echo $footer?>
<?php $this->views->render('footer')?>
</div>

<?php if ($this->views->hasContent('stickyfooter')) : ?>
<div id='stickyfooter' <?=$this->theme->getClassAttributeFor("stickyfooter")?>>
	<?php $this->views->render('fullpage')?>
</div>
<?php endif; ?>

<?php if(isset($jquery)):?><script src='<?=$this->url->asset($jquery)?>'></script><?php endif; ?>

<?php if(isset($javascript_include)): foreach($javascript_include as $val): ?>
<script src='<?=$this->url->asset($val)?>'></script>
<?php endforeach; endif; ?>

<?php if(isset($google_analytics)): ?>
<script>
  var _gaq=[['_setAccount','<?=$google_analytics?>'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
  s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
<?php endif; ?>

</body>
</html>
