<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav'); ?>
<title>Home Page</title>
<style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        margin-top: 50px;
    }

    .table-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-action {
        margin: 2px;
    }
</style>
</head>

<body>
    <div class="container">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>All Users</h3>
                <button class="btn btn-primary" onclick="addUser()"><i class="fas fa-user-plus"></i> Add User</button>
            </div>
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($rows)) {
                        foreach ($rows as $row) {
                    ?>
                            <tr>
                                <td><?php echo esc($row->id); ?></td>
                                <td><?php echo esc($row->username); ?></td>
                                <td><?php echo esc($row->email); ?></td>
                                <td><?php echo esc($row->numbers); ?></td>
                                <td><?php echo esc($row->role); ?></td>
                                <td>
                                    <a class="btn btn-info btn-sm btn-action" href="<?= ROOT ?>/profile/<?= $row->user_id ?>" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a class="btn btn-warning btn-sm btn-action" href="<?= ROOT ?>/profile/edit/<?= $row->user_id ?>" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a class="btn btn-danger btn-sm btn-action" href="<?= ROOT ?>/delete_user/<?= $row->user_id ?>" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function addUser() {
            window.location.href = 'UserAdd';
        }
        </script>
    <?php $this->view('includes/footer'); ?>