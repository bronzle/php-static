<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Error <?= $code ?>: <?= $code_reason ?></title>
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
      display: incall-block;
      padding-left: 15px;
    }
    p {

    }
    p:last-child {
      margin-bottom: 0;
    }

    ul {
      margin-top: 0;
    }
    a, a:hover, a:visited, a:active {
      color: #1070CF;
    }
    section {
      margin-top: 50px;
    }
    h4 {
      margin-bottom: 0;
      margin-left: 10px;
    }
    table {
      table-layout: collapse;
      border-spacing: 0;
      width: 100%;

      background: #F9F9F9;
      border-radius: 3px;
      padding: 10px;
    }
    th {
      text-align: left;
    }
    td {
    }
    tr:nth-child(odd) td {
      background: #F0F0F0;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Error <?= $code ?>:<span><?= $code_reason ?></span></h1>
    <h3><?= $exception->getMessage(); ?></h3>
    <section>
      <span>Possible Locations:</span>
      <ul>
        <?php foreach ($exception->locations as $location): ?>
        <li><?= $location ?></li>
        <?php endforeach; ?>
      </ul>
    </section>
    <section>
      <h4>Backtrace:</h4>
      <table>
        <tr><th>Location</th><th>Function</th></tr>
      <?php foreach ($exception->getTrace() as $trace): ?>
        <tr><td><?= $trace['file'] ?>:<?= $trace['line'] ?></td><td><?= $trace['function'] ?>()</td></tr>
      <?php endforeach; ?>
      </table>
    </section>
  </div>
</body>
</html>
