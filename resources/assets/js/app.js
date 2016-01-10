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
 * APIをcallする
 *
 * @param method HTTPメソッド
 * @param url APIのURL
 * @param data パラメータ
 * @param callback 200が返ってきた際に実行するコールバック関数
 */
function callApi(method, url, data, callback)
{
    data = data == null ? null : data;
    $.ajax({
        'type': method,
        'url': url,
        'data': data
    }).done(function(response) {
        callback(response);
    }).fail(function(data) {
        alert(data.responseJSON.response.errors.join(','));
    });
}