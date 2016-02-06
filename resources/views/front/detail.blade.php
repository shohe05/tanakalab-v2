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
    <style>
        #content {
            max-width: 1000px;
        }
    </style>
@stop
@section('additionalJs')
    <script src="/js/highlight.pack.js"></script>
    <script>
        var id = location.pathname.split(ARTICLE_DETAIL_URL)[1];
        $('#post-form img').attr('src', loginUser().image_path);

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

            $('title').text(article.title + ' | Tanakalab');

            // 削除
            $('#delete-btn').on('click', function() {
                if (!confirm('削除していいですか？')) {
                    return null;
                }
                Article.delete(id).then(function() {
                    location.href = (ARTICLE_INDEX_URL);
                });
            });

            console.log(article);

            // クリップ
            $('#clip-link').on('click', function() {
                var alreadyClipped = false;
                $.each(article.clips, function() {
                    if (this.user_id === loginUser().id) {
                        alreadyClipped = true;
                    }
                });
                if (alreadyClipped) {
                    Article.unclip(id).then(function() {
                        var count = parseInt($('#clip-btn span.count').text());
                        $('#clip-btn span.count').text(count - 1);
                        for(var i=0; i<article.clips.length; i++) {
                            if (article.clips[i].user_id === loginUser().id) {
                                article.clips.splice(i, 1);
                            }
                        }
                        $('#clip-link').text('Clip it!');
                        $('#clip-btn').removeClass('active');
                    });
                } else {
                    Article.clip(id).then(function() {
                        var count = parseInt($('#clip-btn span.count').text());
                        $('#clip-btn span.count').text(count + 1);
                        article.clips.push({user_id: loginUser().id});
                        $('#clip-link').text('Unclip');
                        $('#clip-btn').addClass('active');
                    });
                }

            })


        });

        // コメント投稿
        $('#comments #post-form #submit').on('click', function(){
            var body = $('#comments #post-form textarea[name=body]').val();

            Comment.post(id, body).then(function(data) {
                var commentDom = CommentView.render($.extend(data.response, {user_image_url: loginUser().image_path}));
                $('#post-form').before(commentDom);
                var count = parseInt($('#comment-btn span.count').text());
                $('#comment-btn span.count').text(count + 1);
            });
        });


    </script>
@stop
