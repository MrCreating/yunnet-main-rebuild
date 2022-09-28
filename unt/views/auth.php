<?php
use unt\lang\Language;
?>

<div class="full-container mdl-layout center-container" style="margin-top: -25px">
    <img src="/favicon.ico" class="circle" width="128" height="128">
    <div class="margin-auth-container">
        <div class="mdl-card auth-card default-flex-card mdl-shadow--2dp">
            <div class="margin-default-container">
                <div class="center text-default-margin">
                    <?php echo Language::get()->welcome; ?>
                    <?php echo Language::get()->login_invitation; ?>
                </div>
                <form class="start-auth" autocomplete="off">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-width">
                        <input class="mdl-textfield__input" id="login-input" type="text">
                        <label class="mdl-textfield__label" for="login-input"><?php echo Language::get()->email; ?></label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-width">
                        <input class="mdl-textfield__input" type="password" id="password-input">
                        <label class="mdl-textfield__label" for="password-input"><?php echo Language::get()->password; ?></label>
                    </div>
                    <div class="center one-line-block">
                        <button disabled class="start-auth-btn big-button center mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                            <?php echo Language::get()->login; ?>
                        </button>
                        <div class="start-auth-loader mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active hidden"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>