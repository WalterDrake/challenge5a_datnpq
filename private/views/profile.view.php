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
        <?php $image = get_images($row->avatar); ?>
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

    <!-- Chatbox Section -->
    <?php if (!Auth::i_own_content($row)): ?>
      <div class="chatbox">
        <h5>Note for <?= esc($row->fullname) ?></h5>
        <div class="chat-messages" id="chatMessages"></div>
        <div class="input-group">
          <input type="text" class="form-control" id="chatInput" placeholder="Type a note...">
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
      if (note !== "") {
        let chatBox = document.getElementById("chatMessages");
        let newNote = document.createElement("div");
        newNote.classList.add("alert", "alert-secondary", "mt-2");
        newNote.innerHTML = `${note} <button class='btn btn-sm btn-warning edit-note'>Edit</button> <button class='btn btn-sm btn-danger delete-note'>Delete</button>`;
        chatBox.appendChild(newNote);
        input.value = "";
        chatBox.scrollTop = chatBox.scrollHeight;
      }
    });

    document.getElementById("chatMessages").addEventListener("click", function(event) {
      if (event.target.classList.contains("delete-note")) {
        event.target.parentElement.remove();
      } else if (event.target.classList.contains("edit-note")) {
        let noteDiv = event.target.parentElement;
        let text = noteDiv.firstChild.textContent.trim();
        let newText = prompt("Edit your note:", text);
        if (newText !== null) {
          noteDiv.firstChild.textContent = newText + " ";
        }
      }
    });
  </script>

  <?php $this->view('includes/footer'); ?>
