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
    width: 150px;
    height: 150px;
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
  <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 d-flex flex-row align-items-center shadow-lg" style="max-width: 800px; width: 100%;">
      <!-- Display errors -->
      <?php if (isset($errors) && count($errors) > 0): ?>
        <div class="alert alert-danger mt-3 w-100">
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?= $error ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($row): ?>
        <?php
        $image = get_images($row->avatar);
        ?>
        <div class="avatar-section pr-4">
          <img id="avatar" src="<?= $image ?>" alt="User Avatar" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
        </div>
        <div class="info-section w-100">
          <div class="form-group">
            <label>UserName</label>
            <input type="text" class="form-control" value="<?= esc($row->username) ?>" name='username' disabled>
          </div>
          <div class="form-group">
            <label>Full Name</label>
            <input type="text" class="form-control" value="<?= esc($row->fullname) ?>" name='fullname' disabled>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" id="email" value="<?= esc($row->email) ?>" name='email' disabled>
          </div>
          <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" class="form-control" id="phone" value="<?= esc($row->numbers) ?>" name='numbers' disabled>
          </div>
          <div class="form-group">
            <label>Role</label>
            <input type="text" class="form-control" value="<?= esc($row->role) ?>" name='role' disabled>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name='password' disabled>
          </div>
          <?php if (Auth::access('Administrator') || Auth::access('Teacher') || Auth::i_own_content($row)): ?>
            <a class="btn btn-info btn-sm d-flex align-items-center" href="<?= ROOT ?>/profile/edit/<?= $row->user_id ?>">
              <span>Edit Profile</span>
            </a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <h4>Profile not found</h4>
      <?php endif; ?>
    </div>
  </div>
  <?php $this->view('includes/footer'); ?>