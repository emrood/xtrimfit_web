function filterByDate() {
    // let from = $('input[name="filter_date_from"]').val();
    // let to = $('input[name="filter_date_to"]').val();
    let link = remove('error', removeParam('message', removeParam('to_date', removeParam('from_date', window.location.href)) + "?from_date=" + $('input[name="filter_date_from"]').val() + "&to_date=" + $('input[name="filter_date_to"]').val()));
    window.location.replace(link);
}


function removeParam(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}