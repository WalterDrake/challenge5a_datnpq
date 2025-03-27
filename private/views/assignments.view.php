<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav'); ?>
<title>Assignments</title>
<style>
  .assignment-container {
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }

  .assignment-list {
    margin-top: 20px;
  }
</style>
</head>

<body>
  <div class="container assignment-container">
    <h2 class="text-center">Available Assignments</h2>

    <?php if (Auth::access('Administrator') || Auth::access('Teacher')): ?>
      <a href="<?= ROOT ?>/assignments/add" class="btn btn-primary mb-3">Create Assignment</a>
    <?php endif; ?>

    <table class="table table-bordered assignment-list">
      <thead>
        <tr>
          <th>Title</th>
          <th>Created By</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($row) && is_array($row)): ?>
          <?php foreach ($row as $assignment): ?>
            <tr>
              <td><?= esc($assignment->title) ?></td>
              <td><?= esc($assignment->author) ?></td>
              <td><?= esc($assignment->date) ?></td>
              <td>
                <?php if (Auth::getRole() == 'Teacher' || Auth::getRole() == 'Administrator'): ?>
                  <a href="<?= ROOT ?>/assignments/edit/<?= $assignment->assignment_id ?>" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="<?= ROOT ?>/assignments/delete/<?= $assignment->assignment_id ?>"
                    class="btn btn-danger btn-sm btn-action"
                    onclick="return confirm('Are you sure you want to delete this assignment?');">
                    <i class="fas fa-trash"></i>
                  </a>
                <?php endif; ?>

                <?php if (Auth::getRole() == 'Student' || Auth::getRole() == 'Administrator'): ?>
                  <a href="<?= ROOT ?>/assignments/submit/<?= $assignment->assignment_id ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-upload"></i>
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center">No assignments found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php $this->view('includes/footer'); ?>