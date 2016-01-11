/**
 * ログインしてなかったらログインページにリダイレクトする
 */
function redirectIfNotLogin()
{
    var header = {
        Authorization: 'Bearer' + localStorage.getItem('token')
    };

    _ajax(_makeAjaxOption('GET', CHECK_LOGIN_API_URL, {}, header), function(res) {}, function(res) {
        location.href = LOGIN_URL;
    });
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

    _ajax(_makeAjaxOption(method, url, data, header), callback, function(res) {
        alert(res.responseJSON.response.errors.join(','));
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