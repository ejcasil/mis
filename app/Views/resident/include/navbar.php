 <!-- NAVBAR -->
 <nav>
    <a href="<?= base_url(); ?>resident/dashboard" class="brand"><img src="<?= base_url(); ?>public/assets/images/mis-logo.png" class="navbar-brand me-2" style="width:50px">Web-based BIS</a>
     
    <div class="ms-auto d-flex align-items-center gap-2">
    <span class="mx-1">Welcome, <b class="text-uppercase"><?= session()->get('fname') ?? ''; ?></b></span>
     <span class="divider"></span>
     <div class="profile">
         <div class="img-btn">
            <img src="<?= session()->get('img') ? base_url('writable/uploads/' . session()->get('img')) : 'https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60' ?>" alt="User Image">
             <span class="chevron-icon"><i class="bx bx-chevron-down"></i></span>
         </div>
         <ul class="profile-link">
             <li><a href="<?= base_url(); ?>profile/myAccount2"><i class="bx bxs-user-circle icon"></i>My Account</a></li>
             <?php if (session()->get('household_id')): ?>
                <li>
                <a href="<?= base_url(); ?>resident/edit_household2/<?= session()->get('household_head_id') ?>" class="<?= $check_encoding_schedule ?? ''; ?>">
                    <i class='bx bx-home'></i>Household
                </a>
            </li>
            <?php else : ?>
                <li>
                <a href="<?= base_url(); ?>resident/add2" class="<?= $check_encoding_schedule ?? ''; ?>">
                    <i class='bx bx-home'></i>Household
                </a>
            </li>
            <?php endif; ?>
             <li><a href="<?= base_url(); ?>user/logout"><i class="bx bxs-log-out-circle icon"></i>Logout</a></li>
         </ul>
     </div>
    </div>
 </nav>
 <!-- NAVBAR -->