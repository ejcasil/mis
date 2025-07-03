function showAlert(content, icon = 'success') {
  
  Swal.fire({
      title: "Notification",
      text: content,
      icon: icon,
      position: 'top-center',
      showConfirmButton: false,
      timer: 1500
  });
}
