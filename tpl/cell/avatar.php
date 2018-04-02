<?php /** @var $field \GDO\Avatar\GDT_Avatar **/
use GDO\Avatar\GDO_Avatar;
?>
<div class="gdo-avatar <?=$field->user->getGender()?>">
  <img alt="<?= t('avatar_of', [$field->user->displayNameLabel()]); ?>"
   src="<?= href('Avatar', 'Image', '&ajax=1&file=' . GDO_Avatar::forUser($field->user)->getFileID()); ?>" />
</div>
