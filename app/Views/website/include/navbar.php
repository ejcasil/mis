
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav mx-auto">  <!-- Added mx-auto here to center the links -->
        <li class="navbar-item"><a href="<?= base_url(); ?>">HOME</a></li>
        <li class="navbar-item"><a href="<?= base_url(); ?>all-posts">NEWS AND EVENTS</a></li>
        <li class="navbar-item dropdown">
          <a href="#">ABOUT US</a>
          <ul class="dropdown-menu">
            <li><a href="<?= base_url(); ?>brief-history">Brief History</a></li>
            <li><a href="<?= base_url(); ?>brgy-officials">Brgy Officials</a></li>
            <li><a href="<?= base_url(); ?>issuances">Issuances</a></li>
          </ul>
        </li>
        <li class="navbar-item"><a href="<?= base_url(); ?>login/">LOGIN ACCOUNT</a></li>
      </ul>
    </div>
  </div>
</nav>
