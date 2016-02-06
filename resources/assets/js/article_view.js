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
            var clip_length = this.clips === undefined ? 0 : this.clips.length;
            var comment_length = this.comments === undefined ? 0 : this.comments.length;
            dom += '<li><img src="' + this.user_image_url + '" alt="" /><div class="right"><a href="' + ARTICLE_DETAIL_URL + this.id + '" class="article-link"><h2>' + this.title + '</h2></a><ul><li><i class="fa fa-thumb-tack"></i>&nbsp;&nbsp;' + clip_length +  '</li><li><i class="fa fa-comment"></i>&nbsp;&nbsp;' + comment_length +  '</li><li class="created">Created by ' + this.user_name + ' ' + this.created_at + ' </li></ul></div></li>';
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
        var dom = '<h1 id="article-title">' + article.title;
        $.each(article.tags, function () {
            dom += '<span class="tag">&nbsp;#' + this.name + '</span>';
        });

        if (loginUser().id === article.user_id) {
            dom += '<a href="/article/' + article.id + '/edit"><i id="edit-btn" class="fa fa-pencil"></i></a></a><i id="delete-btn" class="fa fa-trash"></i>';
        }

        dom += '</h1>';
        dom += '<ul>';

        var clip_count = article.clips == undefined ? 0 : article.clips.length;
        var alreadyClipped = false;
        $.each(article.clips, function() {
            if (this.user_id === loginUser().id) {
                alreadyClipped = true;
            }
        });
        var clip_label = alreadyClipped ? 'Unclip' : 'Clip';
        var active = alreadyClipped ? ' active' : '';
        dom += '<li class="star' + active + '" id="clip-btn"><i class="fa fa-thumb-tack"></i>&nbsp;&nbsp;<span id="clip-label">' + clip_label + '</span> <span class="star-count count">' + clip_count + '</span></li>';

        var comment_count = article.comments == undefined ? 0 : article.comments.length;
        dom += '<a href="#post-form"><li class="star" id="comment-btn"><i class="fa fa-comment"></i>&nbsp;&nbsp;Comment  <span class="star-count count">' + comment_count + '</span></li></a>';

        dom += '<li class="created">Created by ' + article.user_name + ' at ' + article.created_at + '</li>';
        dom += '</ul><div id="clip-div"><span id="clip-link">Clip it!</span></div>';
        return dom;
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