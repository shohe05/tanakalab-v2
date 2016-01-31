@extends('layouts.layout')

@section('content')
    <div id="article-header">
    </div>

    <div id="article-body">
        <pre></pre>
    </div>

    <div id="content-title">
        <h2><i class="fa fa-file-text-o"></i>&nbsp;Comments</h2>

        <ul id="comments">
            <li id="post-form">
                <img src="" alt="" />
                <div class="right">
                    <textarea name="body" rows="8" placeholder="Input comment"></textarea>
                    <div id="submit-div">
                        <a id="submit" type="submit"><i class="fa fa-comment"></i>&nbsp;Comment</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@stop

@section('additionalCss')
    <link rel="stylesheet" href="/css/github.css">
@stop
@section('additionalJs')
    <script src="/js/highlight.pack.js"></script>
    <script>
        var id = location.pathname.split(ARTICLE_DETAIL_URL)[1];
        $('#post-form').attr('src', loginUser().image_url);

        Article.find(id).then(function(data) {
            var article = data.response;
            var articleDom = ArticleView.renderDetail(article);

            $('#article-header').append(articleDom.title);
            $('#article-body>pre').append(articleDom.body);

            //TODO: ハイライト系のコードはメソッドに切り出す
            hljs.initHighlightingOnLoad();
            $('#article-body pre code').each(function(i, e) {
                hljs.highlightBlock(e, e.className);
            });

            $('#post-form').before(CommentView.renderComments(article.comments));
        });

        // コメント投稿
        $('#comments #post-form #submit').on('click', function(){
            var body = $('#comments #post-form textarea[name=body]').val();

            Comment.post(id, body).then(function(data) {
                var commentDom = CommentView.render(data.response);
                $('#post-form').before(commentDom);
            });
        })
    </script>
@stop
