<?php if ($this->form) { ?>

    <div class="form-wrapper" >
        <?= $this->form; ?>
    </div>

<?php } else { ?>

    <?= $this->translate('No form found'); ?>

<?php } ?>
