// email signature constructor
// by thiago lima <thiagoaslima@gmail.com
// client: Tecsis Tecnologia e Sistemas Avançados

(function(G, doc, $, Md) {
    'use strict';

    // create a model of the person that is using the system 
    function Person() {
        this.nome = '';
        this.job = '';
        this.email = '';
        this.contact = [{}];
        this.birthday = '';
        this.start = Date.now();
        this.end = '';
    }

    Person.fn = Person.prototype;
    Person.constructor = Function;

    Person.fn.getData = function(str) {
        return this[str];
    };
    Person.fn.setData = function(key, str) {
        this[key] = str;
        $.event.trigger({
            type: 'update',
            field: 'nome',
            info: str
        });
    };
    var User = new Person(),
        holders = {
            nome: 'Nome do Colaborador',
            job: 'Cargo do Colaborador',
            tel: '+55 21 1234 5678'
        };

    // bind events
    $('input[type="text"]').on('keyup', function(e) {
        var value = $.trim($(this).val()),
            key = ($(this).attr('name')).substring(5).toLowerCase();
            currName = User.getData(key);

        if (typeof value === 'string' && value.length > 2 &&
                value !== 'currName') {
            User.setData(key, value);
        } else if (typeof value === 'string' && value.length <= 2) {
            $.event.trigger({
                type: 'update',
                field: 'nome',
                info: holders[key] 
            });
        }
    });


    //view updates
    $(doc).on('update', function(evt) {
        console.log('c:', evt.info);
        switch (evt.field) {
        case 'nome':
            console.log('rolou');
            $('.fieldNome').text(evt.info);
            break;
        }
    });

}(this, this.document, this.jQuery, this.Modernizr));