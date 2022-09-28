function _xmlHttpGet() { let e; try {e = new ActiveXObject("Msxml2.XMLHTTP")} catch (t) { try {e = new ActiveXObject("Microsoft.XMLHTTP");} catch (t) {e = !1;}}; return e || "undefined" == typeof XMLHttpRequest || (e = new XMLHttpRequest()), e; }
String.prototype.isEmpty = function () {let e = this.split("\n").join("");return "" === e.split(" ").join("");};

const unt = {
    data: {
        domain: 'localhost',
        mode: 'http://'
    },
    modules: {},
    url: function (entrypoint = '') {
        return unt.data.mode + (entrypoint === '' ? '' : (entrypoint + '.')) + unt.data.domain + '/';
    },
    form: function (object) {
        let form = new FormData();

        for (let key in object) {
            form.append(key, object[key]);
        }

        return form;
    },
    request: function (params) {
        if (typeof params !== "object")
            return false;

        let config = {
            method: 'GET',
            url: window.location.href,
            success: null,
            error: null,
            progress: null,
            data: null,
            xhrFields: {
                withCredentials: false
            }
        }

        for (let param in params) {
            if (config[param] !== undefined) {
                config[param] = params[param]
            }
        }

        let x = _xmlHttpGet();

        x.withCredentials = config.withCredentials;
        x.open(config.method, config.url);

        if (config.success)
            x.onreadystatechange = function () {
                if (x.readyState !== 4)
                    return;

                if (x.status !== 200 && x.status !== 301 && x.status !== 302 && x.status !== 300) {
                    let error = new Error('Network request code is not 200.');

                    error.responseCode = x.status;
                    return config.error ? config.error(error) : null;
                }

                return config.success(x.responseText, x);
            }

        if (config.error)
            x.onerror = config.error;

        if (config.progress)
            x.onprogress = config.progress;

        return x.send(config.data);
    },
    moduleExists: function (moduleName) {
        return typeof unt[moduleName] !== 'undefined';
    }
};

document.addEventListener('DOMContentLoaded', function () {
    let modulesList = [];
    for (let key in unt.modules) {
        if (typeof unt.modules[key] === 'object' && typeof unt.modules[key].init == 'function') {
            let module = unt.modules[key].init();

            if (!module.name)
                throw new Error('Unable to init unnamed module');
            if (!module.components)
                throw new Error('Components is not defined');

            modulesList.push(module);
        }
    }

    delete unt.modules;
    modulesList.forEach(function (module) {
        unt[module.name] = module.components;
    });
});