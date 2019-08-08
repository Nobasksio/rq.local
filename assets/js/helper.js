function makeForRadio(data) {
    var data = $.map(data, function (obj) {
        obj.value = obj.id;
        obj.text = obj.name_work || obj.name;// replace name with the property used for the text
        return obj;
    });
    return data;
}