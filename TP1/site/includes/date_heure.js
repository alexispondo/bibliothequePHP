function date_heure(id) {
    date = new Date;
    annee = date.getFullYear();
    moi = date.getMonth() + 1;
    if (moi < 10) {
        moi = "0" + moi;
    }
    j = date.getDate();
    h = date.getHours();
    if (h < 10) {
        h = "0" + h;
    }
    m = date.getMinutes();
    if (m < 10) {
        m = "0" + m;
    }
    s = date.getSeconds();
    if (s < 10) {
        s = "0" + s;
    }
    resultat = j + '/' + moi + '/' + annee + ' | ' + h + ':' + m + ':' + s;
    document.getElementById(id).innerHTML = resultat;
    setTimeout('date_heure("' + id + '");', '1000');
    return true;
}