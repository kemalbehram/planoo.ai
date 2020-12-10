window.openModal = (idModal, attachToContainer = false) => {
  console.log("openModal", idModal, attachToContainer);
  if (idModal) {
    if (attachToContainer) {
      $(idModal).appendTo("#modal-container");
    }

    $(idModal).on("click", ".close-on-action", function (e) {
      instances[0].close();
      /*e.preventDefault(); */
    });

    var elems = document.querySelectorAll(idModal);
    if (elems) {
      var instances = M.Modal.init(elems, { 
        dismissible: false,
        preventScrolling: false
       });
      instances[0].open();
    }
  }
};
