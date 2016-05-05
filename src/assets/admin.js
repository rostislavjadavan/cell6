String.prototype.replaceAll = function (find, replace) {
    var str = this;
    return str.replace(new RegExp(find.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g'), replace);
};

function Grid(el) {
    this.element = el;
    this.uid = $(el).data('uid');
    this.gridUid = 'cell6-grid-' + this.uid;
    this.url = $(el).data('url');
    this.columns = $(el).data('columns');
    this.perPage = 10;
    this.data = [];

    this.pageKey = this.gridUid + "-page";
    this.page = 0;
    if (simpleStorage.canUse()) {
        this.page = simpleStorage.get(this.pageKey);
        if (!this.page) {
            this.page = 0;
        }
    }
    
    this.search = '';
    $(this.element).find('.grid-remove-button').hide();
}

Grid.prototype.render = function () {
    this.loadData();
};

Grid.prototype.loadData = function () {
    var jqxhr = $.getJSON(this.url, $.proxy(function (backendData) {
        this.data = backendData;
        this.processData();
    }, this)).fail($.proxy(function () {
        $(this.element).find('.grid-content').html('<div class="alert alert-danger" role="alert"> <strong>Error!</strong> Loading data error.</div>');
    }, this));
};

Grid.prototype.processData = function () {
    if (simpleStorage.canUse()) {
        simpleStorage.set(this.pageKey, this.page);
    }

    var out = '<table class="table">';

    // head
    out += '<thead><tr>';
    for (var i = 0; i < this.columns.length; i++) {
        out += '<th>' + this.columns[i] + '</th>';
    }
    out += '</tr></thead>';

    // data
    var data = this.getData();

    // body
    dataStart = this.page * this.perPage;
    dataEnd = this.page * this.perPage + this.perPage;
    if (dataEnd > data.length) {
        dataEnd = data.length;
    }

    out += '<tbody>';
    for (var row = dataStart; row < dataEnd; row++) {
        out += '<tr>';
        for (var col = 0; col < data[row].length; col++) {
            out += '<td>' + data[row][col] + '</td>';
        }
        out += '</tr>';
    }
    out += '</tbody></table>';

    // pager
    out += '<nav><ul class="pagination">';
    for (i = 0; i < Math.ceil(data.length / this.perPage); i++) {
        out += '<li class="' + (this.page === i ? 'active' : '') + '"><a class="grid-pager-link" data-page="' + i + '" href="">' + (i + 1) + '</a></li>';
    }
    out += '</ul></nav>';

    // output
    $(this.element).find('.grid-content').html(out);

    // pager callback
    $.each($(this.element).find('.grid-pager-link'), $.proxy(function (index, value) {
        $(value).click(value, $.proxy(function (event) {
            this.page = $(value).data('page');
            this.processData();
            event.preventDefault();
        }, this));
    }, this));
    
    $(this.element).find('.grid-search-button').click($.proxy(function() {
        this.page = 0;
        this.search = $(this.element).find('.grid-search-input').val();
        $(this.element).find('.grid-remove-button').show();
        this.processData();
    }, this));
    
    $(this.element).find('.grid-remove-button').click($.proxy(function() {
        this.page = 0;
        this.search = '';
        $(this.element).find('.grid-search-input').val('');
        $(this.element).find('.grid-remove-button').hide();
        this.processData();
    }, this));
};

Grid.prototype.getData = function() {    
    if (this.search === undefined || this.search.length === 0) {
        return this.data;
    }
    var data = [];
    var counter = 0;
    for (var row = 0; row < this.data.length; row++) {        
        for (var col = 0; col < this.data[row].length; col++) {
            if (String(this.data[row][col]).toLowerCase().indexOf(this.search.toLowerCase()) > -1 ) {
                data[counter] = this.data[row];                
                counter++;
            }
        }        
    }
    return data;
};

Grid.prototype.parsePattern = function (pattern, data) {
    var keys = Object.keys(data);
    for (var i = 0; i < keys.length; i++) {
        var key = keys[i];
        pattern = pattern.replace('{' + key + '}', data[key]);
    }
    return pattern;
};

$.each($(".grid"), function (index, value) {
    var grid = new Grid(value);
    grid.render();
});
