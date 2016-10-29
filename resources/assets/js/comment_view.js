var CommentView = {

    /**
     * コメント表示用のhtmlを返却する
     *
     * @param comment
     * @returns {string}
     */
    render: function(comment) {
        return '<li><img src="' + comment.user_image_url + '"><div class="right"><p class="author"><span style="color: grey" class="pull-right time">' + comment.created_at + '</span>' + comment.user_name + '</p><pre class="body">' + comment.body + '</pre></div></li>';
    },

    renderComments: function(comments) {
        var dom = '';
        for(var i=0; i<comments.length; i++) {
            dom += this.render(comments[i]);
        }
        return dom;
    }
};