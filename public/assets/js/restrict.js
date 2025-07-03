$(document).ready(function () {
    function alphanumeric(input) {
      return input.replace(/[^a-zA-Z0-9\s',.()@\-]/g, "");
    }
    function letterOnly(input) {
      return input.replace(/[^a-zA-Z\s]/g, "");
    }
    function numberOnly(input) {
      return input.replace(/[^0-9.]/g, "");
    }
    function cpNumberOnly(input) {
      return input.replace(/[^0-9+]/g, "");
    }

      /*
      ======================================
      OTHER CATEGORIES / BANNER / POST
      ======================================
    */
      $(document).on("input", "#description", function (e) {
        this.value = alphanumeric($(this).val());
      })

      $(document).on("input", "#title", function (e) {
        this.value = alphanumeric($(this).val());
      })

          /*
      ======================================
      BRGY CODE / PUROK CODE
      ======================================
    */
      $(document).on("input", "#brgy", function (e) {
        this.value = alphanumeric($(this).val());
      })

      $(document).on("input", "#purok_zone", function (e) {
        this.value = alphanumeric($(this).val());
      })
  
      $(document).on("input", "#code", function (e) {
        this.value = alphanumeric($(this).val());
      })
          /*
      ======================================
      RESIDENT'S FORM / REGISTRATION FORM / FORGOT PASSWORD
      ======================================
    */
      $(document).on("input", "#lname", function (e) {
        this.value = letterOnly($(this).val());
      })
    
      $(document).on("input", "#fname", function (e) {
        this.value = letterOnly($(this).val());
      })
    
      $(document).on("input", "#mname", function (e) {
        this.value = letterOnly($(this).val());
      })
    
      $(document).on("input", "#suffix", function (e) {
        this.value = letterOnly($(this).val());
      })
    
      $(document).on("input", "#email", function (e) {
        this.value = alphanumeric($(this).val());
      })
    
      $(document).on("input", "#cp", function (e) {
        this.value = cpNumberOnly($(this).val());
      })

      $(document).on("input", "#bplace", function (e) {
        this.value = alphanumeric($(this).val());
      })
  
      $(document).on("input", "#philhealth", function (e) {
        this.value = alphanumeric($(this).val());
      })
  
      $(document).on("input", "#monthly_income", function (e) {
        this.value = numberOnly($(this).val());
      })
  
      $(document).on("input", "#height", function (e) {
        this.value = numberOnly($(this).val());
      })
  
      $(document).on("input", "#weight", function (e) {
        this.value = numberOnly($(this).val());
      })
      
      $(document).on("input", "#street", function (e) {
        this.value = alphanumeric($(this).val());
      })
  
      $(document).on("input", "#house_no", function (e) {
        this.value = numberOnly($(this).val());
      })
              /*
      ======================================
      RESIDENT'S FORM (household member)
      ======================================
    */
      $(document).on("input", "#lname-mbr", function (e) {
        this.value = letterOnly($(this).val());
      })
  
      $(document).on("input", "#fname-mbr", function (e) {
        this.value = letterOnly($(this).val());
      })
  
      $(document).on("input", "#mname-mbr", function (e) {
        this.value = letterOnly($(this).val());
      })
  
      $(document).on("input", "#suffix-mbr", function (e) {
        this.value = letterOnly($(this).val());
      })
  
      $(document).on("input", "#bplace-mbr", function (e) {
        this.value = alphanumeric($(this).val());
      })
  
      $(document).on("input", "#philhealth-mbr", function (e) {
        this.value = alphanumeric($(this).val());
      })
  
      $(document).on("input", "#monthly_income-mbr", function (e) {
        this.value = numberOnly($(this).val());
      })
  
      $(document).on("input", "#cp-mbr", function (e) {
        this.value = cpNumberOnly($(this).val());
      })
  
      $(document).on("input", "#email-mbr", function (e) {
        this.value = alphanumeric($(this).val());
      })
  
      $(document).on("input", "#height-mbr", function (e) {
        this.value = numberOnly($(this).val());
      })
  
      $(document).on("input", "#weight-mbr", function (e) {
        this.value = numberOnly($(this).val());
      })

                   /*
      ======================================
      OTHER HOUSEHOLD INFORMATION (RESIDENT PROFILE)
      ======================================
    */

      $(document).on("input", "#bldg-permit-no", function (e) {
        this.value = alphanumeric($(this).val());
      })

      $(document).on("input", "#lot-no", function (e) {
        this.value = alphanumeric($(this).val());
      })

  
                  /*
      ======================================
      CREATE NEW CATEGORY - RESIDENT FORM (TABLES)
      ======================================
    */
  
      $(document).on("input", ".desc-create", function (e) {
        this.value = alphanumeric($(this).val());
      })
  
                  /*
      ======================================
      CHANGE PASSWORD (MY PROFILE) / USER MANAGEMENT
      ======================================
    */
  
      $(document).on("input", "#username", function (e) {
        this.value = alphanumeric($(this).val());
      })

      $(document).on("input", "#password", function (e) {
        this.value = alphanumeric($(this).val());
      })
  
      $(document).on("input", "#old_password", function (e) {
        this.value = alphanumeric($(this).val());
      })
  
      $(document).on("input", "#new_password", function (e) {
        this.value = alphanumeric($(this).val());
      })

      $(document).on("input", "#rnew_password", function (e) {
        this.value = alphanumeric($(this).val());
      })

    /*
    ======================================
    BRGY OFFICIAL 
    ======================================
  */

    $(document).on('input', "#term", function(e) {
      // Remove any non-numeric characters (except hyphen)
      let value = numberOnly($(this).val());

      // If the input exceeds 9 characters, truncate it to 9 characters
      if (value.length > 8) {
          value = value.substring(0, 8);
      }

      // Add hyphen after the 4th character if not already added
      if (value.length > 4 && value.charAt(4) !== '-') {
          value = value.substring(0, 4) + '-' + value.substring(4);
      }

      // Set the formatted value back to the input field
      $(this).val(value);
  });

                              /*
    ======================================
    FEE MODAL
    ======================================
  */

    $(document).on("input", "#BC-fee", function (e) {
      this.value = numberOnly($(this).val());
    })

    $(document).on("input", "#CI-fee", function (e) {
      this.value = numberOnly($(this).val());
    })

                                  /*
    ======================================
    CERTIFICATION
    ======================================
  */

    $(document).on("input", "#purpose", function (e) {
      this.value = alphanumeric($(this).val());
    })

    $(document).on("input", "#ctc_no", function (e) {
      this.value = numberOnly($(this).val());
    })

                                      /*
    ======================================
    BRGY PROFILE
    ======================================
  */

    $(document).on("input", "#municipality", function (e) {
      this.value = alphanumeric($(this).val());
    })

    $(document).on("input", "#province", function (e) {
      this.value = alphanumeric($(this).val());
    })

    $(document).on("input", "#region", function (e) {
      this.value = alphanumeric($(this).val());
    })

                                      /*
    ======================================
    QUERY BUILDER
    ======================================
  */

    $(document).on("input", "#message", function (e) {
      this.value = alphanumeric($(this).val());
    })

  
  });
  