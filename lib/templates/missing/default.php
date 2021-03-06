<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Internal Error</title>
  <style>
    body {
      font-family: sans-serif;
      color: #444;
      margin: 30px;
    }
    .container {
      width: 600px;
      margin: 0 auto;
      border: 1px solid #CCC;
      padding: 25px 15px;
      border-radius: 2px;
    }
    h1 {
      margin: 0;
    }
    h1 span {
      font-size: 70%;
      color: #999;
      display: inline-block;
      padding-left: 15px;
      vertical-align: top;
      line-height: 37px;
    }
    p {

    }
    p:last-child {
      margin-bottom: 0;
    }
    a, a:hover, a:visited, a:active {
      color: #1070CF;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Internal Error</h1>
    <p>
      Something went wrong, please accept our apologies
    </p>
    <p>
      <a href="<?= request('root_uri') ?>">Return to Home</a>
    </p>
  </div>
</body>
</html>
