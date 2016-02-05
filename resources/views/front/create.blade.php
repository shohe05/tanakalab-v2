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

    <div id="title-preview">
    </div>
    <div id="preview">
    </div>
@stop

@section('additionalCss')
    <link rel="stylesheet" href="/css/github.css">
@stop
@section('additionalJs')
    <script src="/js/highlight.pack.js"></script>
    <script>
        $(function() {
            // プレビュー
            $('#article-post-form #title').keyup(function() {
                var html = '<h1>' + $(this).val() + '</h1>';
                $('#title-preview').html(html);
            });
            $('#article-post-form textarea').keyup(function() {
                var html = marked($(this).val());
                $('#preview').html(html);
                $('#preview pre code').each(function(i, e) {
                    hljs.highlightBlock(e, e.className);
                });
            });

            // タグのフォームを追加
            $('#add-tag-form').on('click', function() {
                $('#tag-form').append('<input type="text" class="tag" placeholder="Tag">');
            });
            $('#add-tag-form').click();

            // 編集の場合
            if (location.pathname.match(/\/article\/\d+\/edit/)) {
                var id = location.pathname.match(/\d+/)[0];
                Article.find(id).then(function(data) {
                    var article = data.response;
                    $('#title').val(article.title);
                    $('#body').val(article.body);
                    $.each(article.tags, function() {
                        console.log(this.name);
                        $('.tag:last-child').val(this.name);
                        $('#add-tag-form').trigger('click');
                    });

                    $('#article-post-form #title').keyup();
                    $('#article-post-form textarea').keyup();
                });
            }

            // 投稿
            $('#article-post-form #submit').on('click', function(){
                var title = $('#article-post-form input[name=title]').val();
                var body = $('#article-post-form textarea[name=body]').val();
                var tags = [];
                $.each($('#article-post-form .tag'), function () {
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