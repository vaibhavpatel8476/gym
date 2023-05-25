    function matchpass() {
        let pass1 = $('#pass').val();
        let pass2 = $('#cpass').val();
        if (pass1 != pass2) {
            $('#passerror').text('not match');
        } else {
            $('#passerror').text('');
        }
    }

    function phonecheck() {
        let num = $('#contact').val();
        
        if (num.match(/\./)) {
            
            $('#numerr').text('dot not allowed');


        } else if (num.length < 10 || num.length > 10) {
            document.getElementById('numerr').innerText = 'Only 10 Digits are allowed';
        } else {
            document.getElementById('numerr').innerText = '';

        }
    }

    function checkdate() {
        let date = new Date();

        let userdate = document.getElementById('dob').value;
        let todaydate = date.getFullYear() + '-' + "0" + (date.getMonth() + 1) + '-' + date.getDate();

        if (userdate < todaydate) {
            document.getElementById('doberr').innerText = '';
        } else if (userdate > todaydate) {
            document.getElementById('doberr').innerText = 'invalid date';

        } else {
            document.getElementById('doberr').innerText = "today's date is not allowed"
        }
    }
    function namecheck(id) {
        let name = document.getElementById(id).value;

        if (name.match(/[0-9]/)) {
            $('#fnameerror').text('numbers are not allow');
            name = '';
        } else if (name.match(/^[a-zA-Z]+$/)) {
            $('#fnameerror').text('');
        } else {
            $('#fnameerror').text('Blank spaces are not allowed');
           
        }

    }



window.start_load = function() {
    $('body').prepend('<di id="preloader2"></di>')
  }
  window.end_load = function() {
    $('#preloader2').fadeOut('fast', function() {
      $(this).remove();
    })
  }