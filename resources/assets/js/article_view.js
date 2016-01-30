// Articleビュー
var ArticleView = {

    /**
     * 記事一覧画面の記事要素のhtmlを返却する
     *
     * @param articles
     * @returns {string}
     */
    renderList: function(articles) {
        var dom = '';
        $.each(articles, function() {
            dom += '<li><a href="' + ARTICLE_DETAIL_URL + this.id + '">' + this.title + '</a></li>';
        });
        return dom;
    },

    /**
     * 記事詳細画面のhtmlを返却する
     *
     * @param article
     * @returns {{title: *, body: *, comments: *}}
     */
    renderDetail: function(article) {
        return {
            title: this._renderDetailTitle(article),
            body: this._renderDetailBody(article.body),
            comments: this._renderDetailComments(article.comments)
        };
    },

    /**
     * 記事詳細画面のタイトル部分のhtmlを返却する
     *
     * @param article
     * @returns {string}
     * @private
     */
    _renderDetailTitle: function(article) {
        var title = '<h1>' + article.title + '</h1>';
        var tags = '<div>';
        $.each(article.tags, function () {
            tags += '<span>' + this.name + '</span>';
        });
        tags += '</div>';
        var user = '<p>' + 'posted by ' + article.user_name + '</p>';
        var created_at = '<p>' + 'posted at ' + article.created_at + '</p>';
        return title + tags + user + created_at;
    },

    /**
     * 記事詳細画面の本文部分のhtmlを返却する
     *
     * @param body
     * @private
     */
    _renderDetailBody: function(body) {
        return marked(body);
    },

    /**
     * 記事詳細画面のコメント部分のhtmlを返却する
     *
     * @param comments
     * @private
     */
    _renderDetailComments: function(comments) {
        var commentsList = '';
        $.each(comments, function() {
            commentsList += CommentView.render(this);
        });
        return commentsList;
    }

};