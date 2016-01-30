// Articleモデル
var Article = {

    /**
     * 全記事取得APIから記事を取得する
     *
     * @returns {*}
     */
    all: function() {
        var deferred = $.Deferred();
        _callAuthApi('GET', GET_ALL_ARTICLE_API_URL, {}, function(data) {
            deferred.resolve(data);
        });
        return deferred.promise();
    },

    /**
     * 記事詳細APIから記事を取得する
     *
     * @param id
     * @returns {*}
     */
    find: function (id) {
        var deferred = $.Deferred();
        _callAuthApi('GET', GET_ARTICLE_API_URL + id, {}, function(data) {
            deferred.resolve(data);
        });
        return deferred.promise();
    },

    post: function (title, body, tags) {
        var deferred = $.Deferred();
        _callAuthApi('POST', POST_ARTICLE_API_URL, {article: {title: title, body: body}, tags: tags}, function(data) {
            deferred.resolve(data);
        });
        return deferred.promise();
    }

};