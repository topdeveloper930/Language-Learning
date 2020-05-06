Date.prototype.setTimezone = function( tz ) {

    const dt = this,
        getOffset = s => ('0' + s).slice(-2),
        y = dt.getFullYear(),
        m = twoNum(dt.getMonth()+1),
        d = twoNum(dt.getDate());
    
    let ret_val = y + '-' + m + '-' + d;

    if( with_time )
        ret_val += ' ' + twoNum(dt.getHours()) + ":" + twoNum(dt.getMinutes()) + ':' + twoNum(dt.getSeconds());

    return ret_val;
};