// email signature constructor
// by thiago lima <thiagoaslima@gmail.com
// client: Tecsis Tecnologia e Sistemas Avançados

(function(G, doc, $, Md, undefined) {
    'use strict';

    // create a private model of the person that is using the system 
    function Person() {
        this.nome = '';
        this.job = '';
        this.email = '';
        this.contacts = [["tel",""]];
        this.listCont = {
            tel: [],
            cel: [],
            fax: []
        },
        this.birthday = '';
    }

    Person.prototype.getData = function(key, index) {
        return (typeof this[index] === 'undefined') ?
            this[key] : this[key][index];
    };
    Person.prototype.setData = function(key, info, index, i2) {
        if (typeof index === 'undefined') {
            this[key] = info;
        } else {
            this[key][index][i2] = info;
        }

        $.event.trigger({
            type: 'update',
            field: key,
            info: info
        });
    };
    Person.prototype.addData = function(key, type, info) {
        this[key].push([type, info]);

        $.event.trigger({
            type: 'insert'
        });
    };
    Person.prototype.removeData = function(key, index) {
        helpers.removeFromArray.call(this[key], index);

        $.event.trigger({
            type: 'delete',
            field: key + index
        });
    };

    var User = G.User = new Person(),
        holders = {
            nome: 'Nome do Colaborador',
            job: 'Cargo do Colaborador',
            tel: '+55 21 1234 5678'
        },
        flags = {
            customContact: false
        },
        helpers = {
            removeFromArray: function(from, to) {
                // Array Remove - By John Resig (MIT Licensed)
                var rest = this.slice((to || from) + 1 || this.length);
                this.length = from < 0 ? this.length + from : from;
                return this.push.apply(this, rest);
            },

            contactsQuery: function(classes) {
                var cl = (classes.split(" ")[0] === classes) ? classes : helpers.getContactClass(classes.split(" ")),
                    data = helpers.getClassNumber(cl);
                    G.console.log('query:', data.cl, '-', data.ind);
                return {
                    cl: data.cl,
                    ind: data.ind
                };
            },

            defField: function(str) {
                return ".field" + str.substring(0,1).toUpperCase() + str.substring(1);
            },

            getContactClass: function(array) {
                var i, len = array.length, resp;
                for (i=0; i<len; i++) {
                    if ($.trim(array[i]).indexOf('contacts') === 0) resp = $.trim(array[i]);
                }
                return resp;
            },
            getClassNumber: function(str) {
                var pos = str.search(/[0-9]/);

                return {
                    cl: str.substring(0, pos),
                    ind: str.substring(pos)
                };
            },

            prepareTemplate: function() {
                var tmp = $('#tmp-cNumber').html(),
                    i = $('.remove').length;
                return tmp.replace(/contacts/g, 'contacts'+i);
            }
        };

    // event handlers
    $('form')
    .on('keyup', 'input[type="text"]', function(e) {
        var value = $.trim($(this).val()),
            key = ($(this).attr('name')).substring(5).toLowerCase(),
            currInfo = User.getData(key);

        if (typeof value === 'string' && value.length > 2 &&
                value !== 'currInfo') {
            User.setData(key, value);
        } else if (typeof value === 'string' && value.length <= 2 && currInfo.length > 2) {
            User.setData(key, holders[key]);
        }
    })
    .on('keyup', 'input[type="tel"]', function(e) {
        var $el = $(this),
            value = $el.val(),
            classes = $el.parent('div').attr('class'),
            data = helpers.contactsQuery(classes);
        User.setData(data.cl, value, data.ind, 1);
    })
    .on('change', 'select', function(e) {
        var $el = $(this),
            value = $el.val(),
            classes = $el.attr('class'),
            data = helpers.contactsQuery(classes);
        User.setData(data.cl, value, data.ind, 0);
    })
    .on('click', '#add', function(e){
        User.addData("contacts", "tel", '');
    })
    .on('click', '.remove', function(e){
        var classes = $(this).parent('div').attr('class'),
            data = helpers.contactsQuery(classes);
        User.removeData(data.cl, data.ind);
    });

    //controller
    $(doc)
    .on('update', function(evt) {
        if (evt.field.indexOf('contacts') !== 0) return;

        var data = helpers.contactsQuery(evt.field),
            type = User.contacts[data.ind];

    });


    //PREVIEW updates
    $('.remove').css('display','none');

    $(doc)
    .on('update', function(evt) {
        if (evt.field.indexOf('contacts') !== 0) {
            var $field = $(helpers.defField(evt.field));
            $field.text(evt.info);
        } else {
            if (flags.customContact) {

            } else {
                // flags.customContact
            }
        }
    })
    .on('insert', function(evt) {
        var tmp = helpers.prepareTemplate();
        $('#add').before(tmp);
        if ($('.remove').length > 1) $('.remove').css('display','block');
    })
    .on('delete', function(evt) {
        $("."+evt.field)
            .nextUntil(':not(br)').remove()
            .end().remove();
        if ($('.remove').length === 1) $('.remove').css('display','none');
    });

}(this, this.document, this.jQuery, this.Modernizr));