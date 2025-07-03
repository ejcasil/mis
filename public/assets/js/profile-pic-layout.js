$(document).ready(function () {
    // >>>>>>>>>>>>>>>>>> OFFICIAL PROFILE AND BRGY PROFILE, USER MANAGEMENT >>>>>>>>>>>>>>>>>> //
    $("#btn-upload-profile").on("click", function () {
      $("#image").click(); // Trigger the file input click
    });
  
    // Update profile pic when the user selects a file
    $("#image").on("change", function (event) {
      let file = event.target.files[0]; // Get the selected file
  
      if (file) {
        // Check if the selected file is an image
        if (file.type.startsWith("image/")) {
          const reader = new FileReader();
  
          // Define what happens when the file is read
          reader.onload = function (e) {
            $(".profile-img").attr("src", e.target.result).show();
          };
  
          reader.readAsDataURL(file);
        } else {
          showToast("Please select a valid image file.");
        }
      }
    });
  
    // >>>>>>>>>>>>>>>>>> MY ACCOUNT >>>>>>>>>>>>>>>>>> //
    $("#btn-upload-profile").on("click", function () {
      $("#file-image").click(); // Trigger the file input click
    });
  
    // Update profile pic when the user selects a file
    $("#file-image").on("change", function (event) {
      let file = event.target.files[0]; // Get the selected file
  
      if (file) {
        // Check if the selected file is an image
        if (file.type.startsWith("image/")) {
          const reader = new FileReader();
  
          // Define what happens when the file is read
          reader.onload = function (e) {
            $(".profile-img").attr("src", e.target.result).show();
          };
  
          reader.readAsDataURL(file);
        } else {
          showToast("Please select a valid image file.");
        }
      }
    });
  
    // >>>>>>>>>>>>>>>>>> RESIDENT PROFILE >>>>>>>>>>>>>>>>>> //
  
    // >> HOUSEHOLD HEAD
    $("#btn-upload-profile").on("click", function () {
      $("#file-head").click(); // Trigger the file input click
    });
  
  
    // >> HOUSEHOLD HEAD
    $("#btn-upload-profile2").on("click", function () {
      $("#file-mbr").click(); // Trigger the file input click
    });
  
    function load_image(head_member, input) {
      var reader = new FileReader();
      var img = head_member === "head" ? $("#img-head") : $("#img-mbr");
  
  
      let file = input.files[0]; // Get the selected file
  
          if (file) {
            // Check if the selected file is an image
            if (file.type.startsWith("image/")) {
              const reader = new FileReader();
      
              // Define what happens when the file is read
              reader.onload = function (e) {
                  img.attr("src", e.target.result).show();
              };
      
              reader.readAsDataURL(file);
            } else {
              showToast("Please select a valid image file.");
            }
          }
  
    }
  
    $("#file-head").change(function (event) {
      var input = event.target;
      load_image("head", input);
    });
  
    $("#file-mbr").change(function (event) {
      var input = event.target;
      load_image("member", input);
    });
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  });
  