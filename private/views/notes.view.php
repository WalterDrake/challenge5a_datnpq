<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav'); ?>
<title>User Notes</title>
<style>
  .note-container {
    max-width: 800px;
    margin: 50px auto;
  }
  .note-card {
    background: white;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  .note-header {
    font-weight: bold;
    color: #333;
  }
</style>
</head>
<body>
  <div class="container note-container">
    <h2 class="mb-4">Notes</h2>
    <?php if (!empty($notes)): ?>
      <?php foreach ($notes as $note): ?>
        <div class="note-card">
          <div class="note-header">From: <?= esc($note->sender) ?></div>
          <ul class="mt-2">
            <?php foreach (json_decode($note->note) as $line): ?>
              <li><?= esc($line) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No notes available.</p>
    <?php endif; ?>
  </div>
  <?php $this->view('includes/footer'); ?>