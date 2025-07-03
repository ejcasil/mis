<?php if ($check_encoding_schedule && $check_encoding_schedule === "disabled") : ?>
<div class="alert alert-success rounded-0 border-0" role="alert">
  <h4 class="alert-heading">Announcement!</h4>
  <p>
    The encoding or updating of Household Profile will be from 
    <?= isset($encoding_schedule) && $encoding_schedule['start_date'] 
        ? DateTime::createFromFormat('m-d-Y', $encoding_schedule['start_date'])->format('F d, Y') 
        : ""; ?> 
    until 
    <?= isset($encoding_schedule) && $encoding_schedule['end_date'] 
        ? DateTime::createFromFormat('m-d-Y', $encoding_schedule['end_date'])->format('F d, Y') 
        : ""; ?>.
  </p>
</div>

<?php endif; ?>
