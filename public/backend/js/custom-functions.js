// JavaScript Document


// GENERAL FUNCTIONS ===================================================

function isEmail(em_address)  {
    var email=/^[A-Za-z0-9]+([_\.-][A-Za-z0-9]+)*@[A-Za-z0-9]+([_\.-][A-Za-z0-9]+)*\.([A-Za-z]){2,4}$/i;
    return (email.test(em_address))
}

function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31
        && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

function SearchByEnter(e,page)
{

    if (window.event) { e = window.event; }
    if ((e.keyCode == 13) || (e.type == 'click'))
    {
        if( page=='Chatbox' || page=='Chatbox') {
            //CallTeamSearch();
            SendChatMessageToUser();
        }

    }
}

function is_array (mixed_var) {
    // From: http://phpjs.org/functions
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Legaev Andrey
    // +   bugfixed by: Cord
    // +   bugfixed by: Manish
    // +   improved by: Onno Marsman
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Nathan Sepulveda
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // %        note 1: In php.js, javascript objects are like php associative arrays, thus JavaScript objects will also
    // %        note 1: return true in this function (except for objects which inherit properties, being thus used as objects),
    // %        note 1: unless you do ini_set('phpjs.objectsAsArrays', 0), in which case only genuine JavaScript arrays
    // %        note 1: will return true
    // *     example 1: is_array(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: true
    // *     example 2: is_array('Kevin van Zonneveld');
    // *     returns 2: false
    // *     example 3: is_array({0: 'Kevin', 1: 'van', 2: 'Zonneveld'});
    // *     returns 3: true
    // *     example 4: is_array(function tmp_a(){this.name = 'Kevin'});
    // *     returns 4: false
    var ini,
        _getFuncName = function (fn) {
            var name = (/\W*function\s+([\w\$]+)\s*\(/).exec(fn);
            if (!name) {
                return '(Anonymous)';
            }
            return name[1];
        },
        _isArray = function (mixed_var) {
            // return Object.prototype.toString.call(mixed_var) === '[object Array]';
            // The above works, but let's do the even more stringent approach: (since Object.prototype.toString could be overridden)
            // Null, Not an object, no length property so couldn't be an Array (or String)
            if (!mixed_var || typeof mixed_var !== 'object' || typeof mixed_var.length !== 'number') {
                return false;
            }
            var len = mixed_var.length;
            mixed_var[mixed_var.length] = 'bogus';
            // The only way I can think of to get around this (or where there would be trouble) would be to have an object defined
            // with a custom "length" getter which changed behavior on each call (or a setter to mess up the following below) or a custom
            // setter for numeric properties, but even that would need to listen for specific indexes; but there should be no false negatives
            // and such a false positive would need to rely on later JavaScript innovations like __defineSetter__
            if (len !== mixed_var.length) { // We know it's an array since length auto-changed with the addition of a
                // numeric property at its length end, so safely get rid of our bogus element
                mixed_var.length -= 1;
                return true;
            }
            // Get rid of the property we added onto a non-array object; only possible
            // side-effect is if the user adds back the property later, it will iterate
            // this property in the older order placement in IE (an order which should not
            // be depended on anyways)
            delete mixed_var[mixed_var.length];
            return false;
        };

    if (!mixed_var || typeof mixed_var !== 'object') {
        return false;
    }

    // BEGIN REDUNDANT
    this.php_js = this.php_js || {};
    this.php_js.ini = this.php_js.ini || {};
    // END REDUNDANT

    ini = this.php_js.ini['phpjs.objectsAsArrays'];

    return _isArray(mixed_var) ||
        // Allow returning true unless user has called
        // ini_set('phpjs.objectsAsArrays', 0) to disallow objects as arrays
        ((!ini || ( // if it's not set to 0 and it's not 'off', check for objects as arrays
                (parseInt(ini.local_value, 10) !== 0 && (!ini.local_value.toLowerCase || ini.local_value.toLowerCase() !== 'off')))
        ) && (
            Object.prototype.toString.call(mixed_var) === '[object Object]' && _getFuncName(mixed_var.constructor) === 'Object' // Most likely a literal and intended as assoc. array
        ));
}



function SubmitAdminLogin(e)
{
    // look for window.event in case event isn't passed in
    if (window.event) { e = window.event; }
    if ((e.keyCode == 13) || (e.type == 'click'))
    {
        AdminLogin();
    }
}


function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31
        && (charCode < 48 || charCode > 57))
        return false;

    return true;
}


function date (format, timestamp) {
    // From: http://phpjs.org/functions
    // +   original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
    // +      parts by: Peter-Paul Koch (http://www.quirksmode.org/js/beat.html)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: MeEtc (http://yass.meetcweb.com)
    // +   improved by: Brad Touesnard
    // +   improved by: Tim Wiel
    // +   improved by: Bryan Elliott
    // +   improved by: David Randall
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Theriault
    // +  derived from: gettimeofday
    // +      input by: majak
    // +   bugfixed by: majak
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Alex
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Theriault
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Theriault
    // +   improved by: Thomas Beaucourt (http://www.webapp.fr)
    // +   improved by: JT
    // +   improved by: Theriault
    // +   improved by: Rafał Kukawski (http://blog.kukawski.pl)
    // +   bugfixed by: omid (http://phpjs.org/functions/380:380#comment_137122)
    // +      input by: Martin
    // +      input by: Alex Wilson
    // +      input by: Haravikk
    // +   improved by: Theriault
    // +   bugfixed by: Chris (http://www.devotis.nl/)
    // %        note 1: Uses global: php_js to store the default timezone
    // %        note 2: Although the function potentially allows timezone info (see notes), it currently does not set
    // %        note 2: per a timezone specified by date_default_timezone_set(). Implementers might use
    // %        note 2: this.php_js.currentTimezoneOffset and this.php_js.currentTimezoneDST set by that function
    // %        note 2: in order to adjust the dates in this function (or our other date functions!) accordingly
    // *     example 1: date('H:m:s \\m \\i\\s \\m\\o\\n\\t\\h', 1062402400);
    // *     returns 1: '09:09:40 m is month'
    // *     example 2: date('F j, Y, g:i a', 1062462400);
    // *     returns 2: 'September 2, 2003, 2:26 am'
    // *     example 3: date('Y W o', 1062462400);
    // *     returns 3: '2003 36 2003'
    // *     example 4: x = date('Y m d', (new Date()).getTime()/1000);
    // *     example 4: (x+'').length == 10 // 2009 01 09
    // *     returns 4: true
    // *     example 5: date('W', 1104534000);
    // *     returns 5: '53'
    // *     example 6: date('B t', 1104534000);
    // *     returns 6: '999 31'
    // *     example 7: date('W U', 1293750000.82); // 2010-12-31
    // *     returns 7: '52 1293750000'
    // *     example 8: date('W', 1293836400); // 2011-01-01
    // *     returns 8: '52'
    // *     example 9: date('W Y-m-d', 1293974054); // 2011-01-02
    // *     returns 9: '52 2011-01-02'
    var that = this,
        jsdate,
        f,
    // Keep this here (works, but for code commented-out
    // below for file size reasons)
    //, tal= [],
        txt_words = ["Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    // trailing backslash -> (dropped)
    // a backslash followed by any character (including backslash) -> the character
    // empty string -> empty string
        formatChr = /\\?(.?)/gi,
        formatChrCb = function (t, s) {
            return f[t] ? f[t]() : s;
        },
        _pad = function (n, c) {
            n = String(n);
            while (n.length < c) {
                n = '0' + n;
            }
            return n;
        };
    f = {
        // Day
        d: function () { // Day of month w/leading 0; 01..31
            return _pad(f.j(), 2);
        },
        D: function () { // Shorthand day name; Mon...Sun
            return f.l().slice(0, 3);
        },
        j: function () { // Day of month; 1..31
            return jsdate.getDate();
        },
        l: function () { // Full day name; Monday...Sunday
            return txt_words[f.w()] + 'day';
        },
        N: function () { // ISO-8601 day of week; 1[Mon]..7[Sun]
            return f.w() || 7;
        },
        S: function(){ // Ordinal suffix for day of month; st, nd, rd, th
            var j = f.j(),
                i = j%10;
            if (i <= 3 && parseInt((j%100)/10, 10) == 1) {
                i = 0;
            }
            return ['st', 'nd', 'rd'][i - 1] || 'th';
        },
        w: function () { // Day of week; 0[Sun]..6[Sat]
            return jsdate.getDay();
        },
        z: function () { // Day of year; 0..365
            var a = new Date(f.Y(), f.n() - 1, f.j()),
                b = new Date(f.Y(), 0, 1);
            return Math.round((a - b) / 864e5);
        },

        // Week
        W: function () { // ISO-8601 week number
            var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3),
                b = new Date(a.getFullYear(), 0, 4);
            return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
        },

        // Month
        F: function () { // Full month name; January...December
            return txt_words[6 + f.n()];
        },
        m: function () { // Month w/leading 0; 01...12
            return _pad(f.n(), 2);
        },
        M: function () { // Shorthand month name; Jan...Dec
            return f.F().slice(0, 3);
        },
        n: function () { // Month; 1...12
            return jsdate.getMonth() + 1;
        },
        t: function () { // Days in month; 28...31
            return (new Date(f.Y(), f.n(), 0)).getDate();
        },

        // Year
        L: function () { // Is leap year?; 0 or 1
            var j = f.Y();
            return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
        },
        o: function () { // ISO-8601 year
            var n = f.n(),
                W = f.W(),
                Y = f.Y();
            return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
        },
        Y: function () { // Full year; e.g. 1980...2010
            return jsdate.getFullYear();
        },
        y: function () { // Last two digits of year; 00...99
            return f.Y().toString().slice(-2);
        },

        // Time
        a: function () { // am or pm
            return jsdate.getHours() > 11 ? "pm" : "am";
        },
        A: function () { // AM or PM
            return f.a().toUpperCase();
        },
        B: function () { // Swatch Internet time; 000..999
            var H = jsdate.getUTCHours() * 36e2,
            // Hours
                i = jsdate.getUTCMinutes() * 60,
            // Minutes
                s = jsdate.getUTCSeconds(); // Seconds
            return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
        },
        g: function () { // 12-Hours; 1..12
            return f.G() % 12 || 12;
        },
        G: function () { // 24-Hours; 0..23
            return jsdate.getHours();
        },
        h: function () { // 12-Hours w/leading 0; 01..12
            return _pad(f.g(), 2);
        },
        H: function () { // 24-Hours w/leading 0; 00..23
            return _pad(f.G(), 2);
        },
        i: function () { // Minutes w/leading 0; 00..59
            return _pad(jsdate.getMinutes(), 2);
        },
        s: function () { // Seconds w/leading 0; 00..59
            return _pad(jsdate.getSeconds(), 2);
        },
        u: function () { // Microseconds; 000000-999000
            return _pad(jsdate.getMilliseconds() * 1000, 6);
        },

        // Timezone
        e: function () { // Timezone identifier; e.g. Atlantic/Azores, ...
            // The following works, but requires inclusion of the very large
            // timezone_abbreviations_list() function.
            /*              return that.date_default_timezone_get();
             */
            throw 'Not supported (see source code of date() for timezone on how to add support)';
        },
        I: function () { // DST observed?; 0 or 1
            // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
            // If they are not equal, then DST is observed.
            var a = new Date(f.Y(), 0),
            // Jan 1
                c = Date.UTC(f.Y(), 0),
            // Jan 1 UTC
                b = new Date(f.Y(), 6),
            // Jul 1
                d = Date.UTC(f.Y(), 6); // Jul 1 UTC
            return ((a - c) !== (b - d)) ? 1 : 0;
        },
        O: function () { // Difference to GMT in hour format; e.g. +0200
            var tzo = jsdate.getTimezoneOffset(),
                a = Math.abs(tzo);
            return (tzo > 0 ? "-" : "+") + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
        },
        P: function () { // Difference to GMT w/colon; e.g. +02:00
            var O = f.O();
            return (O.substr(0, 3) + ":" + O.substr(3, 2));
        },
        T: function () { // Timezone abbreviation; e.g. EST, MDT, ...
            // The following works, but requires inclusion of the very
            // large timezone_abbreviations_list() function.
            /*              var abbr = '', i = 0, os = 0, default = 0;
             if (!tal.length) {
             tal = that.timezone_abbreviations_list();
             }
             if (that.php_js && that.php_js.default_timezone) {
             default = that.php_js.default_timezone;
             for (abbr in tal) {
             for (i=0; i < tal[abbr].length; i++) {
             if (tal[abbr][i].timezone_id === default) {
             return abbr.toUpperCase();
             }
             }
             }
             }
             for (abbr in tal) {
             for (i = 0; i < tal[abbr].length; i++) {
             os = -jsdate.getTimezoneOffset() * 60;
             if (tal[abbr][i].offset === os) {
             return abbr.toUpperCase();
             }
             }
             }
             */
            return 'UTC';
        },
        Z: function () { // Timezone offset in seconds (-43200...50400)
            return -jsdate.getTimezoneOffset() * 60;
        },

        // Full Date/Time
        c: function () { // ISO-8601 date.
            return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
        },
        r: function () { // RFC 2822
            return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
        },
        U: function () { // Seconds since UNIX epoch
            return jsdate / 1000 | 0;
        }
    };
    this.date = function (format, timestamp) {
        that = this;
        jsdate = (timestamp === undefined ? new Date() : // Not provided
                (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
                    new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
        );
        return format.replace(formatChr, formatChrCb);
    };
    return this.date(format, timestamp);
}


// SPECIFIC FUNCTIONS ===================================================





//////////////////////////////////////////////////////////WEBSITE ==========================================================



//////////// top  header Search Bar .................................

////////// VEHICLE ORDERS RELATED FUNCTION =============

function addOrder()
{

    var request_id             = $('#request_id').val();
    var vehicle_id             = $('#veh_id').val();
    var additional_information = $('#additional_information').val();
    var order_amount           = $('#order_amount').val();
    var user_id                = $('#user_id').val();

    //Ajax Call to activate function
    $.ajax({
        type: "POST",
        url: API_URL+'orders/addOrder',
        data: {
            request_id:request_id,
            vehicle_id:vehicle_id ,
            user_id:user_id,
            additional_information:additional_information,
            order_amount:order_amount
        },
        success: function(data)
        {
            if(data.Response=='2000' || data.Response ==2000)
            {

                location.reload();
            }

        }
    });



    $('#CloseButton').trigger('click');

}



function getPieChart(modelid){

    var prices = [];



    if(modelid!=''){


        $.ajax({
            type: "POST",
            async: true,
            dataType: "json",
            url: 'webservice/model_prices.php',
            data: {model_name:modelid},
            success:
                function(data) {

                    var result				= data['result'];
                    var totalRecords		= data['result'].length;

                    var kuz_price_from    =  result['price_from'];
                    var kuz_price_to 	=  result['price_to'];
                    var ebay_price_from =  result['ebay_price_from'];
                    var ebay_price_to 	=  result['ebay_price_to'];
                    var gumtree_price_from =  result['gumtree_price_from'];
                    var gumtree_price_to 	=  result['gumtree_price_to'];

                    //var prices = new Array();
                    prices = [ '$'+kuz_price_from+' to '+'$'+kuz_price_to,  '$'+ebay_price_from+' to '+'$'+ebay_price_to , '$'+gumtree_price_from+' to '+'$'+gumtree_price_to];


                    drawPieChart(prices);


                }
        });

        return false;
    }
};


function drawPieChart(prices){


    // var subcat = $(this).val(); alert(subcat); 

    $('#container').show();
    $(function () {



        //Radialize the colors   colors: ['#C41301','#777777', '#24CBE5','#DDDF00', '#64E572']
        Highcharts.setOptions({
            colors: ['#f5b041','#58d68d', '#e74c3c','#4AC4FA']
        });

        // Build the chart
        $('#container').highcharts({

            /*	chart: {
             type: 'pie',
             options3d: {
             enabled: true,
             alpha: 45,
             beta: 0
             }
             },*/
            title: {
                text: ""
            },
            tooltip: {
                enabled: false,
                animation:true,
                useHTML: true,
                spacingLeft: 10,
                formatter: function() {
                    if(this.point.name =='Kuz') var a = 'Price Range : <b>'+prices[0]+'</b>';
                    if(this.point.name =='Gumtree') var a = 'Price Range : <b>'+prices[2]+'</b>';
                    if(this.point.name =='Ebay') var a = 'Price Range : <b>'+prices[1]+'</b>';
                    if(this.point.name =='Cash Converter') var a = 'Price Range : <b>'+prices[1]+'</b>';
                    return a;
                }
            },
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                },
                events: {
                    load: function(){
                        this.myTooltip = new Highcharts.Tooltip(this, this.options.tooltip);
                    }
                }
            },
            plotOptions: {
                series: {
                    stickyTracking: false,
                    events: {
                        click: function(evt) {
                            this.chart.myTooltip.refresh(evt.point, evt);
                        },
                        mouseOut: function() {
                            this.chart.myTooltip.hide();
                        }
                    } }  ,
                pie: {
                    size:'100%',
                    depth: 30,
                    cursor: 'pointer',
                    allowPointSelect: true,
                    showInLegend: false,
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#fff',
                        softConnector: false,
                        connectorWidth:0,
                        verticalAlign:'bottom',
                        distance:2,
                        overflow: "justify",
                    },
                },
            },
            credits: {
                enabled: false
            },
            series: [{
                type: 'pie',
                name: 'Price Analysis',
                data: [
                    ['Cash Converter', 25],
                    ['Gumtree', 25],
                    ['Ebay', 25],
                    {
                        name:'Kuz',
                        y: 25,
                        sliced: true,
                        selected: true,
                        enableMouseTracking: false
                    },


                ],
                /*dataLabels: {
                 color:'white',
                 distance: function () {
                 if(this.point.name =='Gumtree') return 35;
                 return 25 ;

                 },
                 formatter: function () {
                 return this.point.name;

                 }
                 }*/
            }]
        });

    });


};


///////////////////////////// SELLER PROFILE ///////////////////////////////////////////////////


function GetUrlParms(name)
{
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    if( results == null )
        return "";
    else
        return results[1];
}





<!--HOME PAGE FUNCTIONS-->


function getParentCategories(){

    $.ajax({
        url: "webservice/getCategoriesDropdown.php",
        cache : false,
        type: "GET",
        data: {show_categories: 1 },
        complete : function($response, $status){
            if ($status != "error" && $status != "timeout") {
                $('.parentCategoriesDropdown').html($response.responseText);
                $('#model').prop('selectedIndex',0);
                $(".parentCategoriesDropdown.custom_select").customSelect();

                $(".parentCategoriesDropdown li").on('click', function () {
                    getBrands($(this).text());
                });
            }
        },
        error : function ($responseObj){
            alert("Something went wrong while processing your request.\n\nError => "
                + $responseObj.responseText);
        }
    });
    return false;
};

function getBrands(maincat,selectsubid){

    $.ajax({
        url: "dropdowncat.php",
        cache : false,
        type: "GET",
        data: {getBrand: maincat,sel:selectsubid},
        complete : function($response, $status){
            if ($status != "error" && $status != "timeout") {
                $('.brandsDropdown').html($response.responseText);
                $('#model').prop('selectedIndex',0);
                $(".brandsDropdown.custom_select").customSelect();

                $(".brandsDropdown li").on('click', function () {
                    getBrandModels($(this).text(),maincat);
                });
            }
        },
        error : function ($responseObj){
            alert("Something went wrong while processing your request.\n\nError => "
                + $responseObj.responseText);
        }
    });
    return false;
}
function getBrandModels(subcat,selectmodelid){

    if(selectmodelid === "" || selectmodelid === "undefined"){
        var selectmodelid= $("#tabCategory li.active a").text();
    }
    $.ajax({
        url: "webservice/getCategoriesDropdown.php",
        cache : false,
        type: "GET",
        data: {getModel: subcat,sel:selectmodelid},
        complete : function($response, $status){
            if ($status != "error" && $status != "timeout") {
                //alert('#models');
                $('.priceModel').html($response.responseText);
                $(".priceModel.custom_select").customSelect();

                $(".priceModel li").on('click', function () {
                    //getBrandModelsPrices($(this).text());
                    getPieChart($(this).text());
                });

            }
        },
        error : function ($responseObj){
            alert("Something went wrong while processing your request.\n\nError => "
                + $responseObj.responseText);
        }
    });
    return false;
}

function getBrandModelsPrices(modelId){


    if(modelId === "" || modelId === "undefined"){
        var selectmodelid= $("#tabCategory li.active a").text();
    }
    $.ajax({
        url: "webservice/getCategoriesDropdown.php",
        cache : false,
        type: "GET",
        data: {modelId: modelId },
        complete : function($response, $status){
            if ($status != "error" && $status != "timeout") {

                $('#modelPrices').html($response.responseText);
                $(".modelPrices.custom_select").customSelect();

            }
        },
        error : function ($responseObj){
            alert("Something went wrong while processing your request.\n\nError => "
                + $responseObj.responseText);
        }
    });
    return false;
}



<!--HOME PAGE FUNCTIONS-->