<div class="configWindow">

    <div class="--row">

        <div class="col-xs-12">
            <div class="form-group">
                <label for="form"><?= $this->translateAdmin('form') ?></label><br>
                <?= $this->select('formName', ['width' => '600', 'class' => 'form-control', 'placeholder' => $this->translateAdmin('form'), 'id' => 'formName', 'store' => $this->availableForms]) ?>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <label for="responseTemplate"><?= $this->translateAdmin('response template') ?></label><br>
                <?= $this->href('responseTemplate', ['width' => '600', 'class' => 'form-control', 'id' => 'responseTemplate']) ?>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <label for="sendMailTemplate"><?= $this->translateAdmin('mail template') ?></label><br>
                <?= $this->href('sendMailTemplate', ['width' => '600', 'class' => 'form-control', 'id' => 'sendMailTemplate']) ?>
            </div>
        </div>


    </div>

</div>
