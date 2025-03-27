<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav'); ?>
<title>Add User</title>
<style>
  body {
    background-color: #f8f9fa;
  }

  .add-user-card {
    max-width: 500px;
    margin: 50px auto;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    background: white;
    text-align: center;
  }

  .add-user-card h3 {
    font-weight: bold;
    margin-bottom: 20px;
    color: #343a40;
  }

  .add-user-card .form-control {
    border-radius: 5px;
    border: 1px solid #ced4da;
  }

  .add-user-card button {
    background-color: #007bff;
    border: none;
    color: white;
    padding: 12px;
    border-radius: 5px;
    transition: 0.3s;
    font-weight: bold;
  }

  .add-user-card button:hover {
    background-color: #0056b3;
  }

  .avatar-preview {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto 20px;
    border: 2px solid #dee2e6;
  }

  .avatar-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .form-group label {
    font-weight: bold;
  }
</style>
</head>

<body>
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
    <div class="add-user-card">
      <h3>New User</h3>
      <form method="POST" enctype="multipart/form-data">
        <div class="avatar-preview" id="avatarPreview">
          <img src="<?= ROOT ?>/assets/dinosaur.png">
        </div>
        <div class="form-group">
          <label>Username</label>
          <input type="text" class="form-control" name='username' placeholder="Enter username">
        </div>
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" class="form-control" name='fullname' placeholder="Enter full name">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-control" name='email' placeholder="Enter email">
        </div>
        <div class="form-group">
          <label>Phone Number</label>
          <input type="tel" class="form-control" name='numbers' placeholder="Enter phone number">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="tel" class="form-control" name='password' placeholder="Enter Password">
        </div>
        <div class="form-group">
          <label>Role</label>
          <select class="form-control" name='role'>
            <option selected disabled>Select Role</option>
            <option>Administrator</option>
            <option>Student</option>
            <option>Teacher</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success btn-block">Add User</button>
      </form>
    </div>
  </div>
  <script>
    // Show toasts
    document.addEventListener("DOMContentLoaded", function() {
      let toastElements = document.querySelectorAll('.toast');
      toastElements.forEach(toastEl => {
        let toast = new bootstrap.Toast(toastEl, { delay: 10000 });
        toast.show(); // Show each toast dynamically
      });
    });
  </script>
  <?php $this->view('includes/footer'); ?>