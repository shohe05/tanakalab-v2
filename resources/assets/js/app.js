/**
 * ログインしてなかったらログインページにリダイレクトする
 */
function redirectIfNotLogin() {
    var header = {
        Authorization: 'Bearer' + localStorage.getItem('token'),
        async:false
    };

    _ajax(_makeAjaxOption('GET', CHECK_LOGIN_API_URL, {}, header), function(res) {
        localStorage.setItem('loginUser', JSON.stringify(res.response));
    }, function(res) {
        location.href = LOGIN_URL;
    });
}

function loginUser() {
    return JSON.parse(localStorage.getItem('loginUser'));
}

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('loginUser');
    location.href = LOGIN_URL;
}

/**
 * $.ajaxのラッパー
 *
 * @param option
 * @param successCallback
 * @param failedCallback
 * @private
 */
function _ajax(option, successCallback, failedCallback) {
    $.ajax(
        option
    ).done(function(res) {
        successCallback(res);
    }).fail(function(res) {
        failedCallback(res);
    });
}

/**
 * APIをcallする
 *
 * @param method
 * @param url
 * @param data
 * @param callback
 * @private
 */
function _callApi(method, url, data, callback) {
    _ajax(_makeAjaxOption(method, url, data, {}), callback, function(res) {
        alert(res.responseJSON.response.errors.join(','));
    });
}

/**
 * 認証情報が必要なAPIをcallする
 *
 * @param method
 * @param url
 * @param data
 * @param callback
 * @private
 */
function _callAuthApi(method, url, data, callback) {
    var header = {
        Authorization: 'Bearer' + localStorage.getItem('token')
    };

    _ajax(_makeAjaxOption(method, url, data, header), callback, function(jqXHR, textStatus) {
        alert(jqXHR.responseJSON.response.errors.join('\n'));
    });
}

/**
 * ajaxのオプションを作る
 *
 * @param method
 * @param url
 * @param data
 * @param header
 * @returns {{type: *, url: *, data: *, header: *}}
 * @private
 */
function _makeAjaxOption(method, url, data, header) {
    return {
        type: method,
        url: url,
        data: data,
        headers: header
    };
}

/**
 * クエリ文字列を取り出す
 * @returns {Array}
 */
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");

    for (var i=0; i<vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
        return "";
    }
}