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
    };

    Person.prototype.addData = function(key, type, info) {
        this[key].push([type, info]);
    };

    Person.prototype.removeData = function(key, index) {
        helpers.removeFromArray.call(this[key], index);
    };

}(this, this.document, this.jQuery, this.Modernizr));