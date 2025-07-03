  <!-- SIDERBAR-->
  <section id="sidebar">
      <a href="#" class="brand"><img src="<?= base_url(); ?>public/assets/images/mis-logo.png" class="navbar-brand me-2" style="width:50px">Web-based MIS</a>
      <ul class="side-menu">
          <li><a href="<?= base_url(); ?>main/dashboard" class="<?= (current_url() == base_url('main/dashboard')) ? ' active' : ''; ?>"><i class="bx bxs-dashboard icon"></i>Dashboard</a></li>
          <li class="divider" data-text="Main">Main</li>
          <li>
              <a href="#" class="<?= $check_encoding_schedule ?? ''; ?>"><i class='bx bx-detail icon'></i>Resident Profile <i class="bx bx-chevron-right icon-right"></i></a>
              <ul class="side-dropdown">
                  <li><a href="<?= base_url(); ?>resident/active_inactive3" class="<?= (current_url() == base_url('resident/active_inactive3')) ? ' active' : ''; ?>">Active</a></li>
                  <li><a href="<?= base_url(); ?>resident/for_approval3" class="<?= (current_url() == base_url('resident/for_approval3')) ? ' active' : ''; ?>">For Approval</a></li>
                  <!-- <li><a href="#">Denied</a></li> -->
              </ul>
          </li>
          <li class="divider" data-text="System Settings">System Settings</li>
          <li>
              <a href="#"><i class='bx bx-code-alt icon'></i>Assign Code <i class="bx bx-chevron-right icon-right"></i></a>
              <ul class="side-dropdown">
                  <li><a href="<?= base_url(); ?>BrgyCode3" class="<?= (current_url() == base_url('BrgyCode3')) ? ' active' : ''; ?>">Barangay</a></li>
                  <li><a href="<?= base_url(); ?>PurokZoneCode3" class="<?= (current_url() == base_url('PurokZoneCode3')) ? ' active' : ''; ?>">Purok/Zone</a></li>
              </ul>
          </li>
          <li><a href="<?= base_url(); ?>OtherCategories3" class="<?= (current_url() == base_url('OtherCategories3')) ? ' active' : ''; ?>"><i class="bx bxs-widget icon"></i>Other Categories</a></li>
          <li><a href="<?= base_url(); ?>activity3" class="<?= (current_url() == base_url('activity3')) ? ' active' : ''; ?>"><i class='bx bx-book icon'></i>Activity Log</a></li>
          <li><a href="<?= base_url(); ?>dbms3" class="<?= (current_url() == base_url('dbms3')) ? ' active' : ''; ?>"><i class='bx bx-data icon'></i>Database Management</a></li>
          <li><a href="<?= base_url(); ?>user_management3" class="<?= (current_url() == base_url('user_management3')) ? ' active' : ''; ?>"><i class='bx bx-group icon'></i>User Management</a></li>
          <li><a href="<?= base_url(); ?>query_builder3" class="<?= (current_url() == base_url('query_builder3')) ? ' active' : ''; ?>"><i class='bx bxl-stack-overflow icon'></i>Query Builder</a></li>
          <li>
              <a href="#"><i class='bx bx-map-alt icon'></i>Base Map <i class="bx bx-chevron-right icon-right"></i></a>
              <ul class="side-dropdown">
                  <li><a href="<?= base_url(); ?>maps3" class="<?= (current_url() == base_url('maps3')) ? ' active' : ''; ?>">Maps</a></li>
                  <li><a href="<?= base_url(); ?>maps/import3" class="<?= (current_url() == base_url('maps/import3')) ? ' active' : ''; ?>">Import GeoJSON file</a></li>
              </ul>
          </li>
      </ul>
      
  </section>
  <!-- SIDERBAR-->