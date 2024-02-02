/*
* javascript  helper functions
*/


$(window).load(function() {
    hideLoader();
});


/*--Show Session alert--*/
function ShowSessionAlert(type = 'success' , massage = 'No Massage Set In script ! :-) ') {


    // checking any alert already exist if it is removed
    if($(".session-wrapper").find('.alert').length)
    {
        $(".session-wrapper").find('.alert').remove();
    }


    let session_alert_html =` 
        <div class="alert alert-${type}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
            ${massage}
        </div>`;
    $(".session-wrapper").prepend(session_alert_html);

    //add class show
    //$(".session-wrapper").addClass('show');

}

/*progress bar functions */

function showProgressBar() {
    $('.progress-main-wrap').find('.progress-bar').css({"width":'0%'});
    $('.progress-main-wrap').find('.progress-bar').text('0%');
    $('.progress-main-wrap').addClass('show');
}

function hideProgressBar() {
    $('.progress-main-wrap').find('.progress-bar').css({"width":'0%'});
    $('.progress-main-wrap').find('.progress-bar').text('0%');
    $('.progress-main-wrap').removeClass('show');
}

function toggleProgressBar() {
    $('.progress-main-wrap').toggleClass('show');
}

function updateProgressBar(data) {
    $('.progress-main-wrap').find('.progress-bar').css({"width":data+'%'});
    $('.progress-main-wrap').find('.progress-bar').text(data+'%');
}

function progressBarErrorShow() {
    $('.progress-main-wrap').find('.error-report').css({"display":"block"});
}

function progressBarErrorHide() {
    $('.progress-main-wrap').find('.error-report').css({"display":"none"});
}

// ajax based progress bar download file
function downloadFile(url) {
    // create element
    let requested_file_path = url.replace(app_url+'/','');
    requested_file_path =  requested_file_path.replace(/ /g,'%20');
    //console.log(app_url+'/download-file?file_path='+requested_file_path);
    window.location.href = app_url+'/download-file?file_path='+requested_file_path;
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