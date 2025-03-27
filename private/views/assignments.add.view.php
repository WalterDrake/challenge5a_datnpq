<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav'); ?>
<title>Create Assignment</title>
<style>
  .container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background: white;
  }
</style>

<div class="container">
  <!-- Toast Container (Bootstrap Required) -->
  <div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
      <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
          <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000">
            <div class="d-flex">
              <div class="toast-body">
                <strong>Error:</strong> <?= $error ?>
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
  <h2 class="text-center mb-4">Create Assignment</h2>

  <?php if (isset($errors) && count($errors) > 0): ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?= $error ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label>Title</label>
      <input type="text" class="form-control" name="title" required>
    </div>
    <div class="form-group">
      <label>Description</label>
      <input type="text" class="form-control" name="description">
    </div>
    <?php if (Auth::getRole() == 'Teacher'): ?>
      <div class="form-group">
        <label>Uploaded By</label>
        <input type="text" class="form-control" value="<?= esc(Auth::getUsername()) ?>" disabled>
      </div>
    <?php elseif (Auth::getRole() == 'Administrator'): ?>
      <div class="form-group">
        <label>Uploaded By</label>
        <select class="form-control" name="author" required>
          <option disabled selected>Select a user</option>
          <?php foreach ($data as $user): ?>
            <?php if ($user->role == 'Teacher' || $user->role == 'Administrator'): ?>
              <option value="<?= esc($user->username) ?>"><?= esc($user->username) ?></option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>

    <div class="form-group">
      <label>Date</label>
      <input type="text" class="form-control" value="<?= date('Y-m-d') ?>" name='date' readonly>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Submit Assignment</button>
  </form>
</div>
<script>
  // Show toasts
  document.addEventListener("DOMContentLoaded", function() {
    let toastElements = document.querySelectorAll('.toast');
    toastElements.forEach(toastEl => {
      let toast = new bootstrap.Toast(toastEl, {
        delay: 10000
      });
      toast.show(); // Show each toast dynamically
    });
  });
</script>
<?php $this->view('includes/footer'); ?>