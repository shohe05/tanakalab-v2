@extends('layouts.layout')
<?php $title = 'New Article'; ?>

@section('content')
    <div id="article-post-form">
        <div>
            <input id="title" type="text" name="title" placeholder="Title" required>
        </div>

        <span id="tag-form"></span>
        <a href="#" id="add-tag-form"><i class="fa fa-plus-circle"></i></a>

        <textarea name="body" id="body" rows="20" placeholder="Input with Markdown!" required></textarea>

        <div id="submit-div">
            <a id="submit" type="submit"><i class="fa fa-check"></i>&nbsp;POST IT!</a>
        </div>
    </div>

    {{--<div id="title-preview">--}}
    {{--</div>--}}
    <div id="preview">
    </div>
@stop

@section('additionalCss')
    <link rel="stylesheet" href="/css/github.css">
    <style>
        #content {
            padding-right: 20px;
        }

        #title-preview {
            /*padding-bottom: 30px;*/
        }
    </style>
@stop
@section('additionalJs')
    <script src="/js/highlight.pack.js"></script>
    <script>
        $(function() {
            $('#loading').hide();
            $('#content').show();
            var html = '';

            // DOM取得
            var $articlePostForm = $('#article-post-form');
            var $preview = $('#preview');

            // プレビュー
            $articlePostForm.find('#title').keyup(function() {
                var html = '<h1>' + $articlePostForm.find('#title').val() + '</h1><hr>' + marked($articlePostForm.find('textarea').val());
                $preview.html(html);
            });
            $articlePostForm.find('textarea').keyup(function() {
                var html = '<h1>' + $articlePostForm.find('#title').val() + '</h1><hr>' + marked($articlePostForm.find('textarea').val());
                $preview.html(html);
                $preview.find('pre code').each(function(i, e) {
                    hljs.highlightBlock(e, e.className);
                });
            });

            // タグのフォームを追加
            $articlePostForm.find('#add-tag-form').on('click', function() {
                $articlePostForm.find('#tag-form').append('<input type="text" class="tag" placeholder="Tag">');
            });
            $articlePostForm.find('#add-tag-form').click();

            // 編集の場合
            if (location.pathname.match(/\/article\/\d+\/edit/)) {
                var id = location.pathname.match(/\d+/)[0];
                Article.find(id).then(function(data) {
                    var article = data.response;
                    $articlePostForm.find('#title').val(article.title);
                    $articlePostForm.find('#body').val(article.body);
                    $.each(article.tags, function() {
                        console.log(this.name);
                        $articlePostForm.find('.tag:last-child').val(this.name);
                        $articlePostForm.find('#add-tag-form').trigger('click');
                    });

                    $articlePostForm.find('#title').keyup();
                    $articlePostForm.find('textarea').keyup();
                });
            }

            // 投稿
            $articlePostForm.find('#submit').on('click', function(){
                var title = $articlePostForm.find('input[name=title]').val();
                var body = $articlePostForm.find('textarea[name=body]').val();
                var tags = [];
                $.each($articlePostForm.find('.tag'), function () {
                    if ($(this).val() == "") {
                        return true;
                    }
                    tags.push($(this).val());
                });

                if (location.pathname == '/article/create') {
                    Article.post(title, body, tags).then(function(data) {
                        location.href = ARTICLE_DETAIL_URL + data.response.id;
                    });
                } else {
                    Article.edit(id, title, body, tags).then(function(data) {
                        location.href = ARTICLE_DETAIL_URL + data.response.id;
                    });
                }
            })
        })
    </script>
@stop
