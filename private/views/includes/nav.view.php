<style>
      .navbar {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }
      .navbar-brand {
        font-weight: bold;
        font-size: 1.5rem;
      }
      .nav-link {
        font-size: 1.1rem;
        transition: color 0.3s ease-in-out;
      }
      .nav-link:hover {
        color: #f8c471 !important;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="<?=ROOT?>/home">Welcome <?=ucfirst(Auth::user())?> </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?=ROOT?>/home"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
          </li>
          <li class="nav-item">
            
            <a class="nav-link" href="<?=ROOT?>/profile/<?=Auth::getUser_id()?>/"><i class="fas fa-user"></i> Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=ROOT?>/assignments"><i class="fas fa-book"></i> Assignment</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?=ROOT?>/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </li>
        </ul>
      </div>
    </nav>