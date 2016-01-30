var CommentView = {

    /**
     * コメント表示用のhtmlを返却する
     *
     * @param comment
     * @returns {string}
     */
    render: function(comment) {
        return '<li>' + comment.body + ' by ' + comment.user_name + '</li>';
    }
};