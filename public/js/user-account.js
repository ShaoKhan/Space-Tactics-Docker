$(document).ready(function () {
  const $vacationSwitch = $('#flexSwitchCheckDefault');
  const $vacationLabel = $('#vacationLabel');
  const vacation = $vacationSwitch.data('vac');
  const errormessage = $('#errormessage');

  // Initiale Statusfestlegung beim Laden der Seite
  $vacationSwitch.prop('checked', vacation === 1);
  $vacationLabel
    .text(vacation === 1 ? 'Urlaubsmodus an' : 'Urlaubsmodus aus')
    .toggleClass('vacation-on', vacation === 1)
    .toggleClass('vacation-off', vacation !== 1);

  async function toggleVacationMode() {
    const isChecked = $vacationSwitch.is(':checked') ? 1 : 0;
    const status = isChecked ? 'an' : 'aus';

    // Update der CSS-Klassen und Text auf Basis des aktuellen Status
    $vacationLabel
      .toggleClass('vacation-on', isChecked)
      .toggleClass('vacation-off', !isChecked)
      .text(`Urlaubsmodus ${status}`);

    try {
      const response = await $.ajax({
        url: '/update-user-status',
        method: 'POST',
        data: {
          status: isChecked,
        }
      });

      if (response.status === 'error') {
        errormessage.text(response.message);
        errormessage.show();
        setTimeout(() => {
          errormessage.hide();
        }, 5000);
      }else{
        window.location.reload();
      }

    } catch (error) {
      alert('Es gab ein Problem beim Aktualisieren des Status.');
    }
  }

  // Event-Handler für Statuswechsel
  $vacationSwitch.on('change', toggleVacationMode);
});
