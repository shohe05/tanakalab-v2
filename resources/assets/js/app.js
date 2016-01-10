/**
 * ログインしてなかったらログインページにリダイレクトする
 */
function redirectIfNotLogin()
{
    if (!localStorage.getItem('token')) {
        location.href = LOGIN_URL;
    }
}

/**
 * $.ajaxのラッパー
 *
 * @param option
 * @param callback
 * @private
 */
function _ajax(option, callback) {
    $.ajax(
        option
    ).done(function(res) {
        callback(res);
    }).fail(function(res) {
        alert(res.responseJSON.response.errors.join(','));
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
    _ajax(_makeAjaxOption(method, url, data, {}), callback);
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

    _ajax(_makeAjaxOption(method, url, data, header), callback);
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
        header: header
    };
}