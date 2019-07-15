$(document).ready (function(){

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#datatable').DataTable( {
        "order": [[ 3, "desc" ]],
        "lengthMenu": [[50, -1], [50, "All"]],
        "paging":   true,
        "ordering": true,
        "info":     false
    });

    $('#datatable-wp').DataTable( {
        "order": [[ 3, "desc" ]],
        "lengthMenu": [[50, -1], [50, "All"]],
        "paging":   false,
        "ordering": true,
        "info":     false
    });
    
    $('#btn').hide();

});

/**
 * backlogcount
 */
function backlogcount() {
    $.ajax({
        type: 'POST',
        url: 'ajax.php?type=m&n=dashboard_connector&a=count',
        cache: false,
        success: function(result) {
            $('#backlogcount').html(result);
        }
    });
}

/**
 * systeminfoTime
 */
function systeminfoTime() {
    $.ajax({
        type: 'POST',
        url: 'ajax.php?type=m&n=systeminfo_time&a=datetime',
        cache: false
    }).done(function(html) {
        $('#systeminfo-time').html(html);
    });
}

/**
 * systeminfoLoad
 */
function systeminfoLoad() {
    $.ajax({
        type: 'POST',
        url: 'ajax.php?type=m&n=systeminfo_load&a=load',
        cache: false
    }).done(function(html) {
        $('#systeminfo-load').html(html);
    });
}

/**
 * connectoroutput
 */
function connectoroutput() {
    $.ajax({
        type: 'POST',
        url: 'ajax.php?type=m&n=connector_output&a=ajax',
        cache: false,
        success: function(result) {
            $('#connector-output').html(result);
        }
    });
}

/**
 * cachesize_delay
 */
function cachesize_delay() {
    $(this).delay(3000).queue(function() {
        cachesize();
        $(this).dequeue();
    });
}

/**
 * cachesize
 */
function cachesize() {
    $.ajax({
        type: 'POST',
        url: 'ajax.php?type=c&n=connector&a=cachesize',
        dataType: 'json',
        cache: false,
        success: function(response) {
            //console.log(response.size);
            $('#cachesize').html('<span class="btn ' + response.class + ' btn-sm">Cache ' + response.size + '</span>');
        }
    });
}

/**
 * updater
 */
function updater() {
    var search = function() {
        $.ajax({
            type: 'POST',
            url: 'ajax.php?type=c&n=updater&a=search',
            dataType: 'json',
            cache: false,
            success: function(response) {
                if (response.update) {
                    $('#updatermessage').html(response.message);
                    update();
                } else {
                    $('#updatermessage').html(response.message);
                    no();
                }
            }
        });
    };
    var update = function() {
        $.ajax({
            type: 'POST',
            url: 'ajax.php?type=c&n=updater&a=update',
            dataType: 'json',
            cache: false,
            success: function(response) {
                if (response.update) {
                    $('#updatermessage').html(response.message);
                    console.log(response.output);
                    check();
                } else {
                    $('#updatermessage').html(response.message);
                    console.log(response.output);
                    error();
                }
            }
        });
    };
    var check = function() {
        $.ajax({
            type: 'POST',
            url: 'ajax.php?type=c&n=updater&a=check',
            dataType: 'json',
            cache: false,
            success: function(response) {
                if (response.update) {
                    $('#updatermessage').html(response.message);
                    end();
                } else {
                    $('#updatermessage').html(response.message);
                    console.log(response.output);
                    error();
                }
            }
        });
    };
    
    var no = function() {
        $('#updaterimg').html('<i class="fas fa-check display-3"></i>');
        $('#updaterbtn').html('<a class="btn btn-psc7" title="Fertig" href="index.php">Fertig</a>');
        return;
    };
    
    var error = function() {
        $('#updaterimg').html('<i class="fas fa-exclamation-triangle display-3"></i>');
        $('#updaterbtn').html('<a class="btn btn-psc7" title="Fertig" href="index.php">Abbrechen</a>');
        return;
    };
    
    var end = function() {
        $('#updaterimg').html('<i class="fas fa-check display-3"></i>');
        $('#updaterbtn').html('<a class="btn btn-psc7" title="Fertig" href="index.php">Fertig</a>');
        return;
    };
    
    search();
}

/*
 * https://www.w3schools.com/howto/howto_js_autocomplete.asp
 */
function autocomplete(inp, arr) {
    var currentFocus;
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        closeAllLists();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        this.parentNode.appendChild(a);
        for (i = 0; i < arr.length; i++) {
            if (arr[i].substr(0, val.length).toUpperCase() === val.toUpperCase()) {
                b = document.createElement("DIV");
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                b.addEventListener("click", function(e) {
                    inp.value = this.getElementsByTagName("input")[0].value;
                    closeAllLists();
                });
                a.appendChild(b);
            }
        }
    });
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode === 40) {
            currentFocus++;
            addActive(x);
        } else if (e.keyCode === 38) {
            currentFocus--;
            addActive(x);
        } else if (e.keyCode === 13) {
            e.preventDefault();
            if (currentFocus > -1) {
                if (x) {
                    x[currentFocus].click();
                }
            }
        }
    });
    function addActive(x) {
        if (!x) {
            return false;
        }
        removeActive(x);
        if (currentFocus >= x.length) {
            currentFocus = 0;
        }
        if (currentFocus < 0) {
            currentFocus = (x.length - 1);
        }
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
        for (var i = 0; i < x.length; i++) {
          x[i].classList.remove("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt !== x[i] && elmnt !== inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    document.addEventListener("click", function (e) {
          closeAllLists(e.target);
    });
}
