<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav'); ?>
<title>Submit Assignment</title>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="mb-3">Submit Assignment: <?= esc($row->title) ?></h3>
        <p><strong>Created By:</strong> <?= esc($row->author) ?></p>
        <p><strong>Date:</strong> <?= esc($row->date) ?></p>

        <h5>Assignment Files:</h5>

        <?php
        $files = json_decode($row->location, true); // Decode JSON to array
        ?>
        <ul class="list-group mb-3">
            <?php if (!empty($files) && is_array($files)): ?>
                <?php foreach ($files as $file): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= esc($file) ?>
                        <a href="<?= ROOT . '/uploads/' . $row->assignment_id  . esc($file) ?>" class="btn btn-sm btn-primary" download="<?= esc($file) ?>">Download</a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">No files available.</li>
            <?php endif; ?>
        </ul>

        <h5>Your Submission:</h5>
        <?php
        $files = json_decode($row->submit, true); // Decode JSON to array
        ?>
        <ul class="list-group mb-3">
            <?php if (!empty($files) && is_array($files)): ?>
                <?php foreach ($files as $file): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php if (file_exists(dirname(__DIR__, 2) . "/public/assets/uploads/" . $row->assignment_id . "/submits/" . Auth::getUser_id() . "/" . esc($file))): ?>
                            <?= esc($file) ?>
                            <a href="<?= ROOT . '/uploads/' . $row->assignment_id . "/submits/" . Auth::getUser_id() . esc($file) ?>" class="btn btn-sm btn-info" download>View Submission</a>
                            <form action="<?= ROOT ?>/assignments/dSubmit/<?= $row->assignment_id ?>" method="post" class="d-inline">
                                <input type="hidden" name="filename" value="<?= esc($file) ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        <?php else: ?>
                            <span class="badge badge-danger">File not found</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">No files available.</li>
            <?php endif; ?>
        </ul>


        <form action="<?= ROOT ?>/assignments/submit/<?= $row->assignment_id ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="files">Upload Solution Files:</label>
                <input type="file" class="form-control" name="files[]" multiple required>
            </div>
            <button type="submit" class="btn btn-success mt-2">Upload Solution</button>
        </form>
    </div>
</div>

<?php $this->view('includes/footer'); ?>