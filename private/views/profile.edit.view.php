<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav'); ?>
<title>Edit Profile Page</title>
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

    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card p-4 shadow-lg" style="max-width: 900px; width: 100%;">
            <?php if ($row): ?>
                <?php
                $image = get_images($row->avatar);
                $isEditable = (Auth::getRole() === 'Teacher' || Auth::getRole() === 'Administrator');
                $canEditLimited = (Auth::getRole() === 'Student');
                ?>

                <!-- Profile Form with Horizontal Layout -->
                <form id="profileForm" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Left: Avatar Upload -->
                        <div class="col-md-4 text-center d-flex flex-column align-items-center">
                            <img id="avatar" src="<?= esc($image) ?>" alt="User Avatar" class="rounded-circle shadow"
                                style="width: 150px; height: 150px; object-fit: cover;">
                            <input type="file" id="avatarUpload" name="avatar" class="d-none" accept="image/*">
                            <button type="button" class="btn btn-primary mt-3" onclick="document.getElementById('avatarUpload').click();">
                                Choose Image
                            </button>
                        </div>

                        <!-- Right: User Info -->
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>UserName</label>
                                <input type="text" class="form-control track-change" value="<?= get_var('username', esc($row->username)) ?>" name='username' <?= $isEditable ? '' : 'disabled' ?>>
                            </div>
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" class="form-control track-change" value="<?= get_var('fullname', esc($row->fullname)) ?>" name='fullname' <?= $isEditable ? '' : 'disabled' ?>>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control track-change" id="email" value="<?= get_var('email', esc($row->email)) ?>" name='email' <?= $isEditable || $canEditLimited ? '' : 'disabled' ?>>
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" class="form-control track-change" id="numbers" value="<?= get_var('numbers', esc($row->numbers)) ?>" name='numbers' <?= $isEditable || $canEditLimited ? '' : 'disabled' ?>>
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <select class="form-control track-change" name="role" <?= $isEditable || $canEditLimited ? '' : 'disabled' ?>>
                                    <option disabled <?= empty($row->role) ? 'selected' : '' ?>>Select Role</option>
                                    <option value="Administrator" <?= $row->role == 'Administrator' ? 'selected' : '' ?>>Administrator</option>
                                    <option value="Student" <?= $row->role == 'Student' ? 'selected' : '' ?>>Student</option>
                                    <option value="Teacher" <?= $row->role == 'Teacher' ? 'selected' : '' ?>>Teacher</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control track-change" name='password' <?= $isEditable || $canEditLimited ? '' : 'disabled' ?>>
                            </div>
                            <button type="submit" id="saveBtn" class="btn btn-success btn-block mt-3">Save Changes</button>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <h4 class="text-center">Profile not found</h4>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const avatarUpload = document.getElementById("avatarUpload");
            const avatar = document.getElementById("avatar");
            const profileForm = document.getElementById("profileForm");
            const inputs = document.querySelectorAll(".track-change");
            const initialValues = {};
            let imageChanged = false; // Track if a new image is selected

            // ✅ Show selected image before upload
            avatarUpload.addEventListener("change", function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatar.src = e.target.result;
                        imageChanged = true; // Mark that a new image has been selected
                    };
                    reader.readAsDataURL(file);
                }
            });

            // ✅ Store initial input values
            inputs.forEach(input => {
                initialValues[input.name] = input.value;
            });

            // ✅ Prevent unnecessary form submission for unchanged fields
            profileForm.addEventListener("submit", function(event) {
                inputs.forEach(input => {
                    if (initialValues[input.name] === input.value) {
                        input.removeAttribute("name"); // Remove unchanged fields
                    }
                });

                // If no new image is selected, remove avatar input to avoid sending default image
                if (!imageChanged) {
                    avatarUpload.removeAttribute("name");
                }
            });

            // ✅ Initialize and show Bootstrap toasts
            document.querySelectorAll(".toast").forEach(toastEl => {
                let toastInstance = new bootstrap.Toast(toastEl, {
                    delay: 10000
                });
                toastInstance.show();

                // Allow manual close via the "X" button
                const closeButton = toastEl.querySelector(".btn-close");
                if (closeButton) {
                    closeButton.addEventListener("click", function() {
                        toastInstance.hide();
                    });
                }
            });
        });
    </script>

    <?php $this->view('includes/footer'); ?>