  <!-- SIDERBAR-->
  <section id="sidebar">
      <a href="#" class="brand"><img src="<?= base_url('public/assets/images/mis-logo.png'); ?>" class="navbar-brand me-2" style="width:50px">Web-based BIS</a>
      <ul class="side-menu">
          <li><a href="<?= base_url(); ?>administrator/dashboard" class="<?= (current_url() == base_url('administrator/dashboard')) ? ' active' : ''; ?>"><i class="bx bxs-dashboard icon"></i>Dashboard</a></li>
          <li class="divider" data-text="Main">Main</li>
          <li>
              <a href="#" class="<?= $check_encoding_schedule ?? ''; ?>"><i class='bx bx-detail icon'></i>Resident Profile <i class="bx bx-chevron-right icon-right"></i></a>
              <ul class="side-dropdown">
                  <li><a href="<?= base_url(); ?>resident/active_inactive" class="<?= (current_url() == base_url('resident/active_inactive')) ? ' active' : ''; ?>">Active</a></li>
                  <li><a href="<?= base_url(); ?>resident/for_approval" class="<?= (current_url() == base_url('resident/for_approval')) ? ' active' : ''; ?>">For Approval</a></li>
                  <!-- <li><a href="#">Denied</a></li> -->
              </ul>
          </li>
          <li class="divider" data-text="Issuances">Issuances</li>
          <li>
              <a href="#"><i class='bx bx-printer icon'></i>Printables <i class="bx bx-chevron-right icon-right"></i></a>
              <ul class="side-dropdown">
                  <li><a href="<?= base_url(); ?>certification" class="<?= (current_url() == base_url('certification')) ? ' active' : ''; ?>">Certification / Clearances</a></li>
              </ul>
          </li>
          <li class="divider" data-text="Brgy Officials">Brgy Officials</li>
          <li><a href="<?= base_url(); ?>official" class="<?= (current_url() == base_url('official')) ? ' active' : ''; ?>"><i class='bx bx-detail icon'></i>Official's Profile</a></li>
          <li class="divider" data-text="System Settings">System Settings</li>
          <li>
              <a href="#"><i class='bx bx-code-alt icon'></i>Assign Code <i class="bx bx-chevron-right icon-right"></i></a>
              <ul class="side-dropdown">
                  <li class="collapse"><a href="<?= base_url(); ?>BrgyCode" class="<?= (current_url() == base_url('BrgyCode')) ? ' active' : ''; ?>">Barangay</a></li>
                  <li><a href="<?= base_url(); ?>PurokZoneCode/" class="<?= (current_url() == base_url('PurokZoneCode')) ? ' active' : ''; ?>">Purok/Zone</a></li>
              </ul>
          </li>
          <li><a href="<?= base_url(); ?>OtherCategories" class="<?= (current_url() == base_url('OtherCategories')) ? ' active' : ''; ?>"><i class="bx bxs-widget icon"></i>Other Categories</a></li>
          <!-- <li><a href="<?= base_url(); ?>activity" class="<?= (current_url() == base_url('activity')) ? ' active' : ''; ?>"><i class='bx bx-book icon'></i>Activity Log</a></li> -->
          <!-- <li><a href="<?= base_url(); ?>dbms" class="<?= (current_url() == base_url('dbms')) ? ' active' : ''; ?>"><i class='bx bx-data icon'></i>Database Management</a></li> -->
          <li><a href="<?= base_url(); ?>user_management" class="<?= (current_url() == base_url('user_management')) ? ' active' : ''; ?>"><i class='bx bx-group icon'></i>User Management</a></li>
          <li><a href="<?= base_url(); ?>brgy_profile" class="<?= (current_url() == base_url('brgy_profile')) ? ' active' : ''; ?>"><i class='bx bx-folder-open icon'></i>Barangay Profile</a></li>
          <li><a href="<?= base_url(); ?>query_builder" class="<?= (current_url() == base_url('query_builder')) ? ' active' : ''; ?>"><i class='bx bxl-stack-overflow icon'></i>Query Builder</a></li>

          <li>
              <a href="#"><i class='bx bx-map-alt icon'></i>Base Map <i class="bx bx-chevron-right icon-right"></i></a>
              <ul class="side-dropdown">
                  <li><a href="<?= base_url(); ?>maps" class="<?= (current_url() == base_url('maps')) ? ' active' : ''; ?>">Maps</a></li>
                  <!-- <li><a href="<?= base_url(); ?>maps/import" class="<?= (current_url() == base_url('maps/import')) ? ' active' : ''; ?>">Import GeoJSON file</a></li> -->
              </ul>
          </li>

      </ul>
      
  </section>
  <!-- SIDERBAR-->