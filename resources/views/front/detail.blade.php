<html>
<head>
    <link rel="stylesheet" href="/css/github.css">
    <script src="/js/highlight.pack.js"></script>
    <script src="{{ elixir("js/vendor.js") }}"></script>
    <script src="{{ elixir("js/app.js") }}"></script>
    <script>
        redirectIfNotLogin();
    </script>
</head>
<body>
<h1>Articles Detail</h1>

<div id="article-title"></div>

<div id="article-body"></div>

<div id="comments">
    <ul></ul>
</div>

<div id="comment-post-form">
    <input type="text" name="body">
    <input type="submit" value="投稿">
</div>

<script>
    var id = location.pathname.split(ARTICLE_DETAIL_URL)[1];

    Article.find(id).then(function(data) {
        var article = data.response;
        var articleDom = ArticleView.renderDetail(article);

        $('#article-title').append(articleDom.title);
        $('#article-body').append(articleDom.body);

        //TODO: ハイライト系のコードはメソッドに切り出す
        hljs.initHighlightingOnLoad();
        $('#article-body pre code').each(function(i, e) {
            hljs.highlightBlock(e, e.className);
        });

        $('#comments ul').append(articleDom.comments);
    });

    // コメント投稿
    $('#comment-post-form input[type=submit]').on('click', function(){
        var body = $('#comment-post-form input[name=body]').val();

        Comment.post(id, body).then(function(data) {
            var commentDom = CommentView.render(data.response);
            $('#comments ul').append(commentDom);
        });
    })
</script>
</body>
</html>
