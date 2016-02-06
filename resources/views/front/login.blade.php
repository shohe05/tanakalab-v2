<html>
<head>
    <link rel="stylesheet" href="{{ elixir("css/login.css") }}">
</head>
<body>
<div id="container">
    <div id="login-form">
        <h2>Login Tanakalab</h2>
        <input type='text' id='email' placeholder="Email">
        <input type='password' id='password' placeholder="Password">
        <input type='submit' id='submit' value='Login'>
    </div>
</div>
<script src="{{ elixir("js/vendor.js") }}"></script>
<script src="{{ elixir("js/app.js") }}"></script>
<script>
    $(function() {
        $('#submit').on('click', function() {
            var email = $('#email').val();
            var password = $('#password').val();

            _callApi('POST', LOGIN_API_URL, {'email': email, 'password': password}, function(data){
                var token = data.response.token;
                localStorage.setItem('token', token);
                location.href = ARTICLE_INDEX_URL;
            })
        })
    });
</script>
</body>
</html>
