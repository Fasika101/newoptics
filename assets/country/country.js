var liveObjOfState = {
  Ethiopia: {
    Oromia: ["Nazreth", "Debrezeit", "Mojo", "Dukem"],
    Amhara: ["Bahirdar", "Gonder", "Debrebirhan"],
    Tigray: ["Mekelle", "Shire", "Axum"],
  },
  Kenya: {
    "North Kenya": ["Nairobi", "02"],
    "South Kenya": ["05", "04"],
  },
  Sudan: {
    "North Sudan": ["Khartoum", "08"],
    "South Sudan": ["Juba", "09"],
  },
};
window.onload = function () {
  var selectCountry = document.getElementById("selectCountry"),
    selectState = document.getElementById("selectState"),
    countrystatecitySelect = document.getElementById("countrystatecitySelect");
  for (var country in liveObjOfState) {
    selectCountry.options[selectCountry.options.length] = new Option(
      country,
      country
    );
  }
  selectCountry.onchange = function () {
    selectState.length = 1; // delete all options bar first
    countrystatecitySelect.length = 1; // delete all options bar first
    if (this.selectedIndex < 1) return; // done
    for (var state in liveObjOfState[this.value]) {
      selectState.options[selectState.options.length] = new Option(
        state,
        state
      );
    }
  };
  selectCountry.onchange(); // reset in case page is reloaded
  selectState.onchange = function () {
    countrystatecitySelect.length = 1; // delete all options bar first
    if (this.selectedIndex < 1) return; // done
    var region = liveObjOfState[selectCountry.value][this.value];
    for (var i = 0; i < region.length; i++) {
      countrystatecitySelect.options[countrystatecitySelect.options.length] =
        new Option(region[i], region[i]);
    }
  };
};
