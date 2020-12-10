window.datepickerDefaultOptions = {
  firstDay: 1,
  autoClose: true,
  disableWeekends: true,
  //selectMonths: true, // Creates a dropdown to control month
  yearRange: 5, // Creates a dropdown of 15 years to control year
  format: "dd/mm/yyyy",
  showDaysInNextAndPreviousMonths: true,
  showClearBtn: true,
  i18n: {
    weekdaysAbbrev: ["D", "L", "M", "M", "J", "V", "S"],
    weekdays: [
      "Dimanche",
      "Lundi",
      "Mardi",
      "Mercredi",
      "Jeudi",
      "Vendredi",
      "Samedi",
    ],
    showWeekdaysFull: true,
    weekdaysShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
    months: [
      "Janvier",
      "Février",
      "Mars",
      "Avril",
      "Mai",
      "Juin",
      "Juillet",
      "Août",
      "Septembre",
      "Octobre",
      "Novembre",
      "Décembre",
    ],
    monthsShort: [
      "Janv",
      "Fév",
      "Mars",
      "Avril",
      "Mai",
      "Juin",
      "Juil",
      "Août",
      "Sept",
      "Oct",
      "Nov",
      "Déc",
    ],
    cancel: "Annuler",
    clear: "Effacer",
    done: "Ok",
    nextMonth: "Mois suivant",
    previousMonth: "Mois précédent",
  },
};

window.datepickerAllDatesOptions = {
  firstDay: 1,
  autoClose: true,
  disableWeekends: false,
  //selectMonths: true, // Creates a dropdown to control month
  yearRange: 5, // Creates a dropdown of 15 years to control year
  format: "dd/mm/yyyy",
  showDaysInNextAndPreviousMonths: true,
  showClearBtn: true,
  i18n: {
    weekdaysAbbrev: ["D", "L", "M", "M", "J", "V", "S"],
    weekdays: [
      "Dimanche",
      "Lundi",
      "Mardi",
      "Mercredi",
      "Jeudi",
      "Vendredi",
      "Samedi",
    ],
    showWeekdaysFull: true,
    weekdaysShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
    months: [
      "Janvier",
      "Février",
      "Mars",
      "Avril",
      "Mai",
      "Juin",
      "Juillet",
      "Août",
      "Septembre",
      "Octobre",
      "Novembre",
      "Décembre",
    ],
    monthsShort: [
      "Janv",
      "Fév",
      "Mars",
      "Avril",
      "Mai",
      "Juin",
      "Juil",
      "Août",
      "Sept",
      "Oct",
      "Nov",
      "Déc",
    ],
    cancel: "Annuler",
    clear: "Effacer",
    done: "Ok",
    nextMonth: "Mois suivant",
    previousMonth: "Mois précédent",
  },
};

window.pickerpickerDefaultOptions = {
  showClearBtn: false,
  twelveHour: false,
  i18n: {
    cancel: "Annuler",
    clear: "Effacer",
    done: "Ok",
  },
};

window.modalDefaultOptions = {
  dismissible: false, // Modal can be dismissed by clicking outside of the modal
  opacity: 0.5, // Opacity of modal background
  inDuration: 300, // Transition in duration
  outDuration: 200, // Transition out duration
  endingTop: "10%", // Ending top style attribute
  ready: null,
  complete: null,
};

$(document).ready(function () {
  M.AutoInit();

  $(".collapsible").collapsible({
    accordion: false, // A setting that changes the collapsible behavior to expandable instead of the default accordion style
  });
  // list of selection
  $("select").formSelect();

  $(".datepicker").datepicker(datepickerDefaultOptions);

  $(".timepicker").timepicker(pickerpickerDefaultOptions);

  $(".scrollspy").scrollSpy();

  $(".slider").slider({ full_width: true });

  $(".dropdown-trigger").dropdown({
    //      inDuration: 300,
    //      outDuration: 225,
    constrainWidth: false, // Does not change width of dropdown to that of the activator
    //        hover: true, // Activate on hover
    //      gutter: 0, // Spacing from edge
    coverTrigger: false, // Displays dropdown below the button
    alignment: "right", // Displays dropdown with edge aligned to the left of button
    //        stopPropagation: false // Stops event propagation
  });

  $(".sidenav").sidenav();
});
