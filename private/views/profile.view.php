<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav'); ?>
<title>Profile Page</title>
<style>
  .profile-card {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background: white;
    text-align: center;
  }

  .profile-card img {
    border-radius: 50%;
    width: 120px;
    height: 120px;
    object-fit: cover;
    border: 3px solid #f8c471;
  }

  .profile-card h4 {
    margin-top: 10px;
    font-weight: bold;
  }

  .profile-card .form-control {
    border-radius: 5px;
  }

  .profile-card button {
    background-color: #f8c471;
    border: none;
    color: white;
    padding: 10px;
    border-radius: 5px;
    transition: 0.3s;
  }

  .profile-card button:hover {
    background-color: #e67e22;
  }
</style>
</head>

<body>
  <div class="container">
    <div class="profile-card">
      <img id="avatar" src="https://via.placeholder.com/150" alt="User Avatar">
      <input type="file" id="avatarUpload" class="d-none" accept="image/*">
      <button class="btn btn-primary mt-2" onclick="document.getElementById('avatarUpload').click();">Change Avatar</button>

      <!-- Display errors -->
      <?php if (isset($errors) && count($errors) > 0): ?>
        <div class="alert alert-danger mt-3">
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?= $error ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <h4 id="username">
        <h4>
          <div class="form-group">
            <label>Full Name</label>
            <input type="text" class="form-control" value="<?= get_var('fullname') ?>" name='fullname' disabled>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" id="email" value="<?= get_var('email') ?>"  name='email'>
          </div>
          <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" class="form-control" id="phone" value="<?= get_var('numbers') ?>" name='numbers'>
          </div>
          <div class="form-group">
            <label>Role</label>
            <input type="text" class="form-control" value="<?= get_var('role') ?>" name='role' disabled>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name='password1'>
          </div>
          <div class="form-group">
            <label>RePassword</label>
            <input type="password" class="form-control" name='password2'>
          </div>
          <button class="btn btn-success btn-block">Save Changes</button>
    </div>
  </div>
  <script>
    document.getElementById('avatarUpload').addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('avatar').src = e.target.result;
        }
        reader.readAsDataURL(file);
      }
    });
  </script>
  <?php $this->view('includes/footer'); ?>