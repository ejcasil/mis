 <!-- NAVBAR -->
 <nav>
     <i class="bx bx-menu toggle-sidebar"></i>
     <form action="#">
         <div class="form-group">
             <input type="text" placeholder="Search...">
             <i class="bx bx-search icon"></i>
         </div>
     </form>
     <span class="mx-1">Welcome, <b><?= session()->get('fname') ?? ''; ?></b></span>

     <?php if ((isset($online_request['household_request']) && $online_request['household_request'] !== "0") || (isset($online_request['certification_request']) && $online_request['certification_request'] !== "0") || (isset($online_request['user_request']) && $online_request['user_request'] !== "0")) : ?>
         <li>
             <div class="dropdown">
                 <a href="#" class="position-relative" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bxs-bell-ring fs-5'></i>
                     <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                         <span class="visually-hidden">New alerts</span>
                     </span>
                 </a>

                 <ul class="dropdown-menu">
                     <?php if (isset($online_request['household_request']) && $online_request['household_request'] !== "0"): ?>
                         <li><a class="dropdown-item" href="<?= base_url(); ?>resident/for_approval">Household Approval</a></li>
                     <?php endif; ?>
                     <?php if (isset($online_request['certification_request']) && $online_request['certification_request'] !== "0"): ?>
                         <li><a class="dropdown-item" href="<?= base_url(); ?>certification">Certification</a></li>
                     <?php endif; ?>
                     <?php if (isset($online_request['user_request']) && $online_request['user_request'] !== "0"): ?>
                         <li><a class="dropdown-item" href="<?= base_url(); ?>user_management">User Approval</a></li>
                     <?php endif; ?>
                 </ul>
             </div>
         </li>
     <?php endif; ?>

     <span class="divider"></span>
     <div class="profile">
         <div class="img-btn">
             <img src="<?= session()->get('img') ? base_url('writable/uploads/' . session()->get('img')) : 'https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60' ?>" alt="User Image">
             <span class="chevron-icon"><i class="bx bx-chevron-down"></i></span>
         </div>
         <ul class="profile-link">
             <li><a href="<?= base_url(); ?>profile/myAccount"><i class="bx bxs-user-circle icon"></i>My Account</a></li>
             <li><a href="<?= base_url(); ?>user/logout"><i class="bx bxs-log-out-circle icon"></i>Logout</a></li>
         </ul>
     </div>
 </nav>
 <!-- NAVBAR -->