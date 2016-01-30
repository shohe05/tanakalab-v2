// APIのURL
const _API_BASE_URL = '/api/v1';
const LOGIN_API_URL = _API_BASE_URL + '/auth/login';
const CHECK_LOGIN_API_URL = _API_BASE_URL + '/auth/check';
const GET_ALL_ARTICLE_API_URL = _API_BASE_URL + '/articles';
const GET_ARTICLE_API_URL = _API_BASE_URL + '/articles/';
const POST_COMMENT_API_URL = function(article_id) { return _API_BASE_URL + '/articles/' + article_id + '/comments/create' };
const POST_ARTICLE_API_URL = _API_BASE_URL + '/articles/create';

// フロントアプリのURL
const LOGIN_URL = '/login';
const ARTICLE_INDEX_URL = '/';
const ARTICLE_DETAIL_URL = '/article/';