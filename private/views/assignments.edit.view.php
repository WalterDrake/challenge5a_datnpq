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
        <p><strong>Created By:</strong> <?= esc($row->author) ?></p>
        <p><strong>Date:</strong> <?= esc($row->date) ?></p>

        <h5>Current Assignment Files:</h5>
        <?php
        $files = json_decode($row->location, true); // Decode JSON to array
        ?>
        <ul class="list-group mb-3">
            <?php if (!empty($files) && is_array($files)): ?>
                <?php foreach ($files as $file): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= esc($file) ?>
                        <a href="<?= ROOT . '/uploads/' . $row->assignment_id . "/assignments/" . esc($file) ?>" class="btn btn-sm btn-primary" download="<?= esc($file) ?>">Download</a>
                        <form action="<?= ROOT ?>/assignments/dEdit/<?= $row->assignment_id ?>" method="post" class="d-inline">
                                <input type="hidden" name="filename" value="<?= esc($file) ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">No files available.</li>
            <?php endif; ?>
        </ul>

        <h5>Upload Additional Files:</h5>
        <form action="<?= ROOT ?>/assignments/edit/<?= $row->assignment_id ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" class="form-control" name="files[]" multiple>
            </div>
            <button type="submit" class="btn btn-success mt-2">Upload Files</button>
        </form>

        <h5 class="mt-4">Submitted Solutions:</h5>
        
        <?php
        // retrieve all files from the 'submits' directory
        $submitsDir = dirname(__DIR__, 2) . "/public/assets/uploads/" . $row->assignment_id . "/submits/";

        $files = [];

        // Check if the directory exists
        if (is_dir($submitsDir)) {
            // Scan for all user folders inside 'submits'
            $userDirs = array_diff(scandir($submitsDir), array('.', '..'));

            foreach ($userDirs as $userDir) {
                $userPath = $submitsDir . $userDir . "/";

                if (is_dir($userPath)) {
                    // Scan for all files in user directories
                    $userFiles = array_diff(scandir($userPath), array('.', '..'));

                    foreach ($userFiles as $file) {
                        $files[] = [
                            "path" => $userPath . $file,
                            "url" => ROOT . "/uploads/" . $row->assignment_id . "/submits/" . $userDir . "/" . $file,
                            "name" => $file,
                            "user" => $userDir
                        ];
                    }
                }
            }
        }
        ?>
        <ul class="list-group mb-3">
            <?php if (!empty($files)): ?>
                <?php foreach ($files as $fileData): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= esc($fileData['name']) ?>
                        <a href="<?= esc($fileData['url']) ?>" class="btn btn-sm btn-info" download>View Submission</a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">No files available.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<?php $this->view('includes/footer'); ?>