<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav'); ?>
<title>Home Page</title>
</head>
<body>
    <h1>Home Page</h1>
    <?php 
    echo "<pre>";
    print_r($rows);
    
    ?>
    
<?php $this->view('includes/footer'); ?>
