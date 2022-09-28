unt.modules.auth = {
    authAccount: function (login, password) {
        return new Promise(function (resolve, reject) {
            unt.request({
                url: unt.url('api') + 'auth.get',
                method: 'POST',
                data: unt.form({
                    login: login,
                    password: password,
                    app_id: 1
                }),
                withCredentials: true,
                success: function (response) {
                    console.log(response);
                },
                error: function (error) {
                    console.log(error);
                }
            })
        })
    },
    init: function () {
        let module = this;

        let authForm = document.getElementsByClassName('start-auth')[0];
        if (!authForm)
            throw new Error('Failed to find auth form');

        let loginInput = document.getElementById('login-input');
        let passwordInput = document.getElementById('password-input');
        let authButton = authForm.getElementsByClassName('start-auth-btn')[0];
        let authLoader = authForm.getElementsByClassName('start-auth-loader')[0];

        loginInput.addEventListener('input', function () {
            if (loginInput.value.isEmpty() || passwordInput.value.isEmpty()) {
                authButton.setAttribute('disabled', 'true');
            } else {
                authButton.removeAttribute('disabled');
            }
        });
        passwordInput.addEventListener('input', function () {
            if (loginInput.value.isEmpty() || passwordInput.value.isEmpty()) {
                authButton.setAttribute('disabled', 'true');
            } else {
                authButton.removeAttribute('disabled');
            }
        });

        authForm.addEventListener('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();

            loginInput.setAttribute('disabled', 'true');
            passwordInput.setAttribute('disabled', 'true');

            authButton.classList.add('hidden');
            authLoader.classList.remove('hidden');

            module.authAccount(loginInput.value, passwordInput.value);
        });

        return {
            name: 'auth',
            components: this
        }
    }
}
