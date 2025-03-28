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

  .chatbox {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 300px;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background: white;
  }

  .chat-messages {
    max-height: 200px;
    overflow-y: auto;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: #f9f9f9;
  }
</style>
</head>

<body>
  <div class="container d-flex justify-content-center align-items-center" style="height: 100vh; flex-direction: column;">
    <div class="card p-4 d-flex flex-row align-items-center shadow-lg" style="max-width: 800px; width: 100%;">
      <?php if ($row): ?>
        <?php $image = get_images($row->avatar, $row->user_id); ?>
        <div class="avatar-section pr-4">
          <img id="avatar" src="<?= esc($image) ?>" alt="User Avatar" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
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

    <!-- Notes Section -->
    <?php if (!Auth::i_own_content($row)): ?>
      <div class="chatbox">
        <h5>Notes for <?= esc($row->fullname) ?></h5>
        <div class="chat-messages" id="chatMessages">
          <?php if (!empty($notes)): ?>
            <?php
            $note_id = $notes->note_id; // Store the note_id for all messages
            $note_messages = json_decode($notes->note);
            ?>
            <input type="hidden" id="note_id" value="<?= esc($note_id) ?>"> <!-- Hidden note_id -->
            <?php foreach ($note_messages as $note_message): ?>
              <div class='alert alert-secondary mt-2' data-id="<?= esc($note_id) ?>">
                <?= esc($note_message) ?>
                <button class='btn btn-sm btn-warning edit-note'>Edit</button>
                <button class='btn btn-sm btn-danger delete-note'>Delete</button>
              </div>
            <?php endforeach; ?>
            <input type="hidden" id="note_id" value="<?= esc($note_id) ?>"> <!-- Hidden note_id -->
          <?php else: ?>
            <input type="hidden" id="note_id" value=""> <!-- No existing note -->
          <?php endif; ?>
        </div>
        <div class="input-group">
          <input type="text" class="form-control" id="chatInput" placeholder="Type a note...">
          <input type="hidden" id="receiver_id" value="<?= esc($row->user_id) ?>">
          <div class="input-group-append">
            <button class="btn btn-primary" id="sendMessage">Send</button>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script>
    document.getElementById("sendMessage").addEventListener("click", function() {
      let input = document.getElementById("chatInput");
      let note = input.value.trim();
      let receiver_id = document.getElementById("receiver_id").value;
      let note_id = document.getElementById("note_id").value; // Get the note_id

      if (note !== "") {
        let bodyData = `note=${encodeURIComponent(note)}`;
        if (note_id) {
          bodyData += `&note_id=${note_id}`; // Update existing note
        } else {
          bodyData += `&receiver_id=${receiver_id}&sender_id=<?= Auth::getUser_id() ?>`; // New note
        }
        fetch("<?= ROOT ?>/profile/send", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            body: bodyData
          })
          .then(response => location.reload());
      }
    });

    document.getElementById("chatMessages").addEventListener("click", function(event) {
      if (event.target.classList.contains("delete-note")) {
        let noteDiv = event.target.parentElement;
        let noteId = noteDiv.getAttribute("data-id"); // ID of the note entry
        let noteText = noteDiv.firstChild.textContent.trim(); // Specific note content

        fetch("<?= ROOT ?>/profile/deleteNote/" + noteId, {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `note=${encodeURIComponent(noteText)}`
          }).then(response => response.json())
          .then(data => {
            if (data.success) {
              noteDiv.remove();
            } else {
              alert("Error deleting note");
            }
          })
          .catch(error => console.error("Error:", error));
      } else if (event.target.classList.contains("edit-note")) {
        let noteDiv = event.target.parentElement;
        let text = noteDiv.firstChild.textContent.trim();
        let newText = prompt("Edit your note:", text);

        if (newText !== null) {
          let noteId = noteDiv.getAttribute("data-id");

          fetch("<?= ROOT ?>/profile/editNote/" + noteId, {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded"
              },
              body: `old_note=${encodeURIComponent(text)}&new_note=${encodeURIComponent(newText)}`
            }).then(response => response.json())
            .then(data => {
              if (data.success) {
                noteDiv.firstChild.textContent = newText + " ";
              } else {
                alert("Error updating note");
              }
            })
            .catch(error => console.error("Error:", error));
        }
      }
    });
  </script>

  <?php $this->view('includes/footer'); ?>