var Comment = {

    /**
     * コメントを投稿する
     *
     * @param article_id
     * @param body
     * @returns {*}
     */
    post: function(article_id, body) {
        var deferred = $.Deferred();
        _callAuthApi('POST', POST_COMMENT_API_URL(article_id), {body: body}, function(data) {
            deferred.resolve(data);
        });
        return deferred.promise();
    }
};