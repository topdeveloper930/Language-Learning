Date.prototype.formatStd = function( with_time ) {

    with_time = ( typeof with_time !== 'undefined' ) ? with_time : true;

    const dt = this,
        twoNum = s => ('0' + s).slice(-2),
        y = dt.getFullYear(),
        m = twoNum(dt.getMonth()+1),
        d = twoNum(dt.getDate());
    
    let ret_val = y + '-' + m + '-' + d;

    if( with_time )
        ret_val += ' ' + twoNum(dt.getHours()) + ":" + twoNum(dt.getMinutes()) + ':' + twoNum(dt.getSeconds());

    return ret_val;
};