function doc_ready() {

    if (typeof work_bill_url != "undefined")
        checkbox_billed(work_bill_url);
    if ($('#bill_check_all').length > 0)
        checkall_billworks();
    if ($('#bill_uncheck_all').length > 0)
        uncheckall_billworks();

    // Customs doc ready
    if (typeof doc_ready_home != "undefined")
        doc_ready_home();
}


function checkbox_billed(work_bill_url) {

    $('.bill-page input.chk_billed').each(function() {
        var checkbox = this;
        $(this).click(function() {

            var id_bill = parseInt($('#id_bill').val());
            if (id_bill == 0) {
                alert('Select a bill');
                this.checked = !this.checked;
                return false;
            }

            var id_work = this.id.replace(/billed_/,'');
            if (!id_work)
                return;

            $.ajax({
                type: "POST",
                url: work_bill_url.replace(/id_work/,id_work).replace(/id_bill/,id_bill),
                data: "checked="+this.checked,
                dataType: "json",
                success: function(result){
                    if (typeof(result) != "undefined") {
                        selected_hours_ajustment($(checkbox).closest('table.records_list').find('.selected_hours').get(0));
                        return highlight(checkbox);
                    }
                },
                error: function(e) {
                    return false;
                }
            });

        });
    });
}

/**
 * checkall_billworks
 * used in Bill's works list
 */
function checkall_billworks() {
    $('#bill_check_all').click(function(e) {
        e.preventDefault();

        if (!confirm('Are you sure to add all works to the selected bill?')) return;

        var id_bill = parseInt($('#form_bill').val());
        if (id_bill == 0) {
            alert('Select a bill');
            return;
        }
        var realhref = $(this).attr('href').replace(/\/[0-9\-]*\/billall/,'/'+id_bill+'/billall');
        $.ajax({
            type: "POST",
            url: realhref,
            data: null,
            dataType: "json",
            success: function(result){
                $.map(result.works, function(val,i) {
                    document.getElementById('billed_'+val).checked = true;
                    selected_hours_ajustment_all();
                    highlight('#billed_'+val);
                });
                return true;
            },
            error: function(e) {
                console.log(e.responseText)
                return false;
            }
        });
    });
}

/**
 * uncheckall_billworks
 * used in Bill's works list
 */
function uncheckall_billworks() {
    $('#bill_uncheck_all').click(function(e) {
        e.preventDefault();

        if (!confirm('Are you sure to remove all the selected bill works?')) return;

        var id_bill = parseInt($('#form_bill').val());
        if (id_bill == 0) {
            alert('Select a bill');
            return;
        }
        var realhref = $(this).attr('href').replace(/\/[0-9\-]*\/unbillall/,'/'+id_bill+'/unbillall');

        $.ajax({
            type: "POST",
            url: realhref,
            data: null,
            dataType: "json",
            success: function(result){
                $.map(result.works, function(val,i) {
                    document.getElementById('billed_'+val).checked = false;
                    selected_hours_ajustment_all();
                    highlight('#billed_'+val);
                });
                return true;
            },
            error: function(e) {
                console.log(e.responseText)
                return false;
            }
        });
    });
}

function selected_hours_ajustment(selected_hours) {
    if ($(selected_hours).length < 1)
        return;

    var container = $(selected_hours).closest('table').get(0);
    var selected_hours_value = 0;

    $(container).find('td.val-hours').each(function() {
        if ($(this).nextAll('.val-billed:first').find('input').get(0).checked)
            selected_hours_value += parseFloat($(this).html().replace(/,/,'.'));
    });

    $(selected_hours).html(selected_hours_value.wackyRound(2).toString().replace(/\./,',')+ ' h');
}

function selected_hours_ajustment_all(val) {
    $('.selected_hours').each(function() {
        selected_hours_ajustment(this);
    });
}


function highlight(obj) {
    $(obj).parent().effect("highlight",{color:"#ffffaa"},3000);
}


function call_alert($msg) {
    // TODO : JQuery Modals
    alert($msg);
    return false;
}

function call_confirm($msg) {
    // TODO : JQuery Modals
    return confirm($msg);
}

// Instead of using tofixed() on float.
// http://stackoverflow.com/a/2862144/229088
Number.prototype.wackyRound = function(places){
    var multiplier = Math.pow(10, places+2); // get two extra digits
    var fixed = Math.floor(this*multiplier); // convert to integer
    fixed += 44; // round down on anything less than x.xxx56
    fixed = Math.floor(fixed/100); // chop off last 2 digits
    return fixed/Math.pow(10, places);
}