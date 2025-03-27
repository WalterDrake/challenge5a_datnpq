<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav'); ?>
<title>Edit Assignment</title>

<?php if (!Auth::access('Administrator') && !Auth::access('Teacher')): ?>
    <div class="container mt-5">
        <div class="alert alert-danger">You do not have permission to access this page.</div>
    </div>
    <?php exit; ?>
<?php endif; ?>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="mb-3">Title: <?= esc($row->title) ?></h3>
        <p><strong>Uploaded By:</strong> <?= esc($row->author) ?></p>
        <p><strong>Date:</strong> <?= esc($row->date) ?></p>

        <h5>Current Assignment Files:</h5>
        <ul class="list-group mb-3">
            <?php if (empty($row->location)): ?>
                <li class="list-group-item">No files uploaded.</li>
            <?php else: ?>
               <?= var_dump(json_decode($row->location, true)); ?>
            <?php foreach ($row->location as $file): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= esc($file) ?>
                    <a href="<?= ROOT . '/uploads/' . esc($file) ?>" class="btn btn-sm btn-primary" download>Download</a>
                </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <h5>Upload Additional Files:</h5>
        <form action="<?= ROOT ?>/assignments/edit/<?= $row->assignment_id ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="assignment_files">Upload New Assignment Files:</label>
                <input type="file" class="form-control" name="assignment_files[]" multiple>
            </div>
            <button type="submit" class="btn btn-success mt-2">Upload Files</button>
        </form>

        <h5 class="mt-4">Submitted Solutions:</h5>
        <ul class="list-group">
            <?php if(empty($row->submit)): ?>
                <li class="list-group-item">No files submitted.</li>
                <?php else: ?>
            <?php foreach ($submitted_solutions as $solution): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= esc($solution->student_name) ?> - <?= esc($solution->filename) ?>
                    <a href="<?= ROOT . '/uploads/' . esc($solution->filepath) ?>" class="btn btn-sm btn-info" download>View Solution</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</div>
<?php $this->view('includes/footer'); ?>