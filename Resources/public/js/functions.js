function doc_ready() {

    if (typeof work_bill_url != "undefined")
        checkbox_billed(work_bill_url);

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

function highlight(obj) {
    $(obj).parent().addClass('highlight');
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