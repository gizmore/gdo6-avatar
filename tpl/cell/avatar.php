<?php /** @var $field \GDO\Avatar\GDT_Avatar **/
use GDO\Avatar\GDO_Avatar;
$az = $field->avatarSize;
?>
<div
 class="gdo-avatar <?=$field->user->getGender()?>"
 style="width: <?=$az?>px; height: <?=$az?>px;"
 <?=$field->htmlAttributes()?>
  ><img alt="<?= t('avatar_of', [$field->user->displayNameLabel()]); ?>"
   src="<?= href('Avatar', 'Image', '&ajax=1&file=' . GDO_Avatar::forUser($field->user)->getFileID()); ?>"
   style="padding: <?=round($az/24)?>px; width: <?=$az?>px; height: <?=$az?>px;" /></div>
