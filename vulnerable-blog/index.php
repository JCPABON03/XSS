<?php
// index.php - Laboratorio educativo: NO usar en producción
$commentsFile = __DIR__ . '/comments.txt';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['author'], $_POST['comment'])) {
    $author = trim($_POST['author']);
    $comment = trim($_POST['comment']);
    $entry = json_encode(['time'=>time(),'author'=>$author,'comment'=>$comment]) . PHP_EOL;
    file_put_contents($commentsFile, $entry, FILE_APPEND | LOCK_EX);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$comments = [];
if (file_exists($commentsFile)) {
    $lines = file($commentsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $data = json_decode($line, true);
        if ($data) $comments[] = $data;
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mi Blog (Laboratorio)</title>
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <header class="site-header">
    <h1>Mi Blog - Laboratorio</h1>
    <p class="tagline">Un sitio pequeño para practicar seguridad web en un entorno controlado.</p>
  </header>

  <main class="container">
    <section class="post">
      <h2>Entrada de ejemplo</h2>
      <p>Este es un contenido ficticio. Abajo puedes dejar comentarios.</p>
    </section>

    <section class="comment-form">
      <h3>Deja un comentario</h3>
      <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label>Autor
          <input name="author" required maxlength="100">
        </label>
        <label>Comentario
          <textarea name="comment" rows="5" required maxlength="1000"></textarea>
        </label>
        <button type="submit">Enviar</button>
      </form>
    </section>

    <section class="comments">
      <h3>Comentarios</h3>
      <?php if (empty($comments)): ?>
        <p>No hay comentarios aún.</p>
      <?php else: ?>
        <?php foreach (array_reverse($comments) as $c): ?>
          <article class="comment">
            <div class="meta">
              <strong><?= $c['author'] ?></strong>
              <time><?= date('Y-m-d H:i:s', $c['time']) ?></time>
            </div>
            <div class="body">
              <!-- SALIDA SIN ESCAPAR INTENCIONALMENTE (vulnerable para laboratorio) -->
              <?= $c['comment'] ?>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>
  </main>

  <footer class="site-footer">
    <small>Laboratorio local — no exponer a Internet</small>
  </footer>

  <script src="assets/js/app.js"></script>
</body>
</html>
