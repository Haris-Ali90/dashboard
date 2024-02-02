/*--Show Session alert--*/
function ShowSessionAlert(type = 'success' , massage = 'No Massage Set In script ! :-) ') {

    // checking any alert already exist if it is removed
    if($(".x_content").find('.alert').length)
    {
        $(".x_content").find('.alert').remove();
    }

    let session_alert_html =` 
        <div class="alert alert-${type}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
            ${massage}
        </div>`;

    $(".x_content").prepend(session_alert_html);

}

/*window loader*/
$(window).load(function() {
    hideLoader();
});

/*loader show hide function*/
function showLoader() {
    $('.loader-mian-wrap').addClass('show');
}

function hideLoader() {
    $('.loader-mian-wrap').removeClass('show');
}

function toggleLoader() {
    $('.loader-mian-wrap').toggleClass('show');
}

// url Query to jaosn
function urlQueryTOJason(query) {
    let params_obj = {};
    let query_string = query.split("?");
    //checking url has query exist
    if(query_string.length > 1)
    {
        let single_parm = query_string[1].split('&');
        for(let single_value in single_parm)
        {
            let values = single_parm[single_value].split('=');
            params_obj[values[0]] = values[1];
        }
    }


    return params_obj;
}