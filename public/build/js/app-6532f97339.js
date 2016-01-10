function redirectIfNotLogin()
{
    if (!localStorage.getItem('token')) {
        location.href = LOGIN_URL;
    }
}

function callApi(method, url, data, callback)
{
    $.ajax({
        'type': method,
        'url': url,
        'data': data
    }).done(function(data) {
        callback(data);
    }).fail(function(data) {
        alert(data.responseJSON.response.errors.join(','));
    });
}
const _API_BASE_URL = '/api/v1';

const LOGIN_API_URL = _API_BASE_URL + '/auth/login';
const ARTICLE_INDEX_URL = '/';
const LOGIN_URL = '/login';
//# sourceMappingURL=app.js.map
