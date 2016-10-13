 // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
      };

      var latitude = 43.073755;
      var longitude = -89.392902;

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        if(!checkNearby(place.geometry.location.lat(), place.geometry.location.lng())){
            window.alert("We're sorry, that address is out of our delivery bounds, please enter another address");
          return;
        }
        else{
          document.getElementById("address-error").innerHTML = "";
        }

        // for (var component in componentForm) {
        //   document.getElementById(component).value = '';
        //   document.getElementById(component).disabled = false;
        // }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        // for (var i = 0; i < place.address_components.length; i++) {
        //   var addressType = place.address_components[i].types[0];
        //   if (componentForm[addressType]) {
        //     var val = place.address_components[i][componentForm[addressType]];
        //     document.getElementById(addressType).value = val;
        //   }
        // }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        
        
            var geolocation = {
              lat: 43.0730556,
              lng: -89.4011111
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: 8046
            });
            autocomplete.setBounds(circle.getBounds());
       
        
      }

      function checkNearby(lat, long){
          var R = 6371e3; // metres
          var φ1 = toRad(latitude);
          var φ2 = toRad(lat);
          var Δφ = toRad(lat-latitude);
          var Δλ = toRad(long-longitude);

          var a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                  Math.cos(φ1) * Math.cos(φ2) *
                  Math.sin(Δλ/2) * Math.sin(Δλ/2);
          var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

          var d = R * c;
          return (16093.40-d) > 0;
      }
      function toRad(x) {
        return x * Math.PI / 180;
      }