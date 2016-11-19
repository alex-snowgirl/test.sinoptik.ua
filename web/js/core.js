/**
 * Created by snowgirl on 11/19/16.
 */

var db = function () {
    if (window.hasOwnProperty('localStorage')) {
        this.st = localStorage;
    } else if (window.hasOwnProperty('sessionStorage')) {
        this.st = sessionStorage;
    } else {
        throw new Error('Implement other db storage!');
    }
};
db.prototype.setItem = function (k, v) {
    return this.st.setItem(k, JSON.stringify({v: v}));
};
db.prototype.getItem = function (k, def) {
    var v = this.st.getItem(k);
    return null !== v ? JSON.parse(v).v : typeof def != 'undefined' ? def : null;
};


var app = function () {
    this.db = null;
};

app.prototype.getDb = function () {
    return this.db ? this.db : this.db = new db();
};

app.prototype.addCourseHistory = function (course) {
    var tmp = this.getCourseHistory();
    tmp.unshift(course);

    if (tmp.length > 10) {
        tmp.pop();
    }

    this.getDb().setItem('courseHistory', tmp);
};

app.prototype.getCourseHistory = function () {
    return this.getDb().getItem('courseHistory', []);
};

app.prototype.drawCourseHistory = function (containerId) {
    var tmp = [];
    var history = this.getCourseHistory();

    for (var i = 0, l = history.length; i < l; i++) {
        tmp.push('<tr>');
        tmp.push('<td><a href="' + history[i]['uri'] + '">' + history[i]['currency'] + ' / ' + history[i]['bank'] + '</a></td>');
        tmp.push('<td>' + history[i]['sell'] + ' / ' + history[i]['buy'] + '</td>');
        tmp.push('<td>' + history[i]['date'] + '</td>');
        tmp.push('</tr>');
    }

    if (tmp) {
        document.getElementById(containerId).innerHTML = tmp.join('');
    }
};

app.prototype.liveSubmit = function (formId) {
    var $form = $('#' + formId);
    $form.find('[name]').on('change', function () {
        $form.submit();
    });
};
