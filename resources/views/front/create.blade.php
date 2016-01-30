<html>
<head>
    <link rel="stylesheet" href="/css/github.css">
    <script src="/js/highlight.pack.js"></script>
    <script src="{{ elixir("js/vendor.js") }}"></script>
    <script src="{{ elixir("js/app.js") }}"></script>
    <script>
        redirectIfNotLogin();
    </script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        #article-post-form {
            float: left;
            overflow: auto;
            width: 45%;
            height: 100%;
            margin: 0;
            padding: 10px;
            border: none;
            resize: none;
        }

        #preview {
            float: right;
            overflow: auto;
            width: 50%;
            height: 100%;
            margin: 0;
            padding: 10px;
            background-color: #EEE;
        }
    </style>
</head>
<body>

<div id="article-post-form">
    <div>
        <input type="text" name="title" placeholder="タイトル">
    </div>

    <div>
        <div id="tag-form"></div>
        <a href="#" id="add-tag-form">タグを追加</a>
    </div>

    <div>
        <textarea name="body" cols="60" rows="40" placeholder="本文"></textarea>
    </div>

    <div>
        <input type="submit" value="投稿">
    </div>
</div>

<div id="preview">
</div>

<script>
    $(function() {
        // プレビュー
        $('#article-post-form textarea').keyup(function() {
            var html = marked($(this).val());
            $('#preview').html(html);

            /*----- 追加 ----- */
            $('#preview pre code').each(function(i, e) {
                hljs.highlightBlock(e, e.className);
            });
        });

        // タグのフォームを追加
        $('#add-tag-form').on('click', function() {
            $('#tag-form').append('<input type="text" class="tag-body" placeholder="タグ">');
        });
        $('#add-tag-form').click();

        // 投稿
        $('#article-post-form input[type=submit]').on('click', function(){
            var title = $('#article-post-form input[name=title]').val();
            var body = $('#article-post-form textarea[name=body]').val();
            var tags = [];
            $.each($('#article-post-form .tag-body'), function () {
                if ($(this).val() == "") {
                    return true;
                }
                tags.push($(this).val());
            });

            Article.post(title, body, tags).then(function(data) {
                location.href = ARTICLE_DETAIL_URL + data.response.id;
            });
        })
    })
</script>
</body>
</html>