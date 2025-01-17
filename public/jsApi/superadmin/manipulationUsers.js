// $.ajaxSetup({
//     headers:{
//         'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
//     }
// })

// Declaration 
const url = "http://127.0.0.1:8000/users"
const divMessage = $('#message-alert');

// Functions
//--> Post user informations
function postDataUser() {
    $.ajax({
        type: "POST",
        url: url,
        async: false,
        data: {
            first_name : $('#first-name-user').val(),
            last_name : $('#last-name-user').val(),
            cin : $('#cin-user').val(),
            phone_number : $('#phone-number-user').val(),
            birthday_date : $('#birtday-user').val(),
            email : $('#email-user').val(),
            role : $('#roles-user').val(),
            
        },
        dataType: "json",
        success: (response) => {
            divMessage.html(messageComponants("Utilisateur ajouter avec success", "success", "fa-check"));
        },
        error: (xhr, status, error) => {
            var err = JSON.parse(xhr.responseText);
            divMessage.html(messageComponants(err.message, "danger", "fa-times"));
        }
    })

}


//Events
//--> Submit Form
$( ".form-user" ).submit(function( event ) {
    event.preventDefault();
    postDataUser();
}) 
