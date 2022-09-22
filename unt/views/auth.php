<?php
use unt\lang\Language;
?>

<div class="full-container mdl-layout center-container">
    <div class="mdl-card auth-card default-flex-card mdl-shadow--2dp">
        <div class="margin-default-container">
            <form>
                <div class="mdl-textfield mdl-js-textfield full-width">
                    <input class="mdl-textfield__input" id="login-input" type="text">
                    <label class="mdl-textfield__label" for="login-input"><?php echo Language::get()->email; ?></label>
                </div>
                <div class="mdl-textfield mdl-js-textfield full-width">
                    <input class="mdl-textfield__input" type="password" id="password-input">
                    <label class="mdl-textfield__label" for="password-input"><?php echo Language::get()->password; ?></label>
                </div>
            </form>
        </div>
    </div>
</div>