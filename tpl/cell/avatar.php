<?php /** @var $field \GDO\Avatar\GDT_Avatar **/ ?>
<div class="gdo-avatar <?=$field->user->getGender()?>">
  <img alt="<?= t('avatar_of', [$field->user->displayNameLabel()]); ?>"
   src="<?= href('Avatar', 'Image', '&ajax=1&file=' . $field->gdo->getVar('avatar_file_id')); ?>" />
</div>
