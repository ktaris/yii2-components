var Matrim = {
    DEBUG: true,
    /**
     * Muestra mensajes para debug, en base a su bandera de debug.
     *
     * @param {string} cadena cadena a mostrar en el debug.
     * @param {object} objeto objeto para mostrar contenido.
     */
    debugLog: function (cadena, objeto = false) {
        if (this.DEBUG == true) {
            if (!objeto) {
                console.log(cadena);
            } else {
                console.log(cadena, objeto);
            }
        }
    },
    /**
     * ========================================================================
     * ========================================================================
     * === MANEJO DE NÚMEROS ==================================================
     * ========================================================================
     * ========================================================================
     */
    /**
     * Convierte un valor a su equivalente a número decimal, con los decimales
     * especificados por el parámetro "decimales".
     * @param  {string} valor     cadena a ser convertida
     * @param  {number} decimales entero, de 2 a 6, con la cantidad de decimales
     * @return {string}           número convertido con decimales especificados
     */
    convertirDecimal: function (valor, decimales) {
        var v = Number.parseFloat(valor);
        if (Number.isFinite(v)) {
            valor = this.redondearImporte(v, decimales);
        } else {
            valor = undefined;
        }

        return valor;
    },
    /**
     * Convierte un valor a su equivalente a número decimal, con los decimales
     * especificados por el parámetro "decimales". Posteriormente, vuelve a
     * convertir la cadena en float, para cálculos posteriores.
     * @param  {string} valor     cadena a ser convertida
     * @param  {number} decimales entero, de 2 a 6, con la cantidad de decimales
     * @return {number}           número flotante para más cálculos
     */
    convertirDecimal2: function (valor, decimales) {
        var decimalString = this.convertirDecimal(valor, decimales);

        var numero = Number.parseFloat(decimalString);

        // Nos aseguramos que tenga un valor, para futuros cálculos.
        if (!Number.isFinite(numero)) {
            numero = 0;
        }

        return numero;
    },
    /**
     * Función de redondeo utilizada en el cálculo de importes, impuestos, subtotal y total.
     *
     * Fuente: http://www.mediacollege.com/internet/javascript/number/round.html
     *
     * @param {number} number   número a ser redondeado.
     * @param {number} decimals número de decimales para redondear.
     */
    redondearImporte: function (number, decimals = 2) {
        decimals = (decimals > 6) ? 6 : decimals;
        // Nuevo código con nueva librería.
        var newnumber = new Decimal(number);
        var numeroSalida = newnumber.toDecimalPlaces(decimals).toNumber().toString();
        return numeroSalida;
    },
    /**
     * Wrap para la función _.reduce, que se encarga de convertir los números
     * a flotantes de dos decimales, para posteriormente realizar la suma.
     * @param  {string} clase de los elementos a ser sumados.
     * @return {number} suma de todos los elementos de la lista.
     */
    reduceFloat: function (elementListName) {
        return _.reduce($(elementListName), function (memo, element) {
            return memo + Matrim.convertirDecimal2($(element).val());
        }, 0);
    },
    suma2: function (num1, num2) {
        return Matrim.convertirDecimal2(Matrim.convertirDecimal2(num1) + Matrim.convertirDecimal2(num2));
    },
    resta2: function (num1, num2) {
        return Matrim.convertirDecimal2(Matrim.convertirDecimal2(num1) - Matrim.convertirDecimal2(num2));
    },
    producto2: function (num1, num2) {
        return Matrim.convertirDecimal2(Matrim.convertirDecimal2(num1) * Matrim.convertirDecimal2(num2));
    },
    porcentaje2: function (num1, num2) {
        return Matrim.convertirDecimal2(Matrim.producto2(num1, num2) / 100);
    },
};

Matrim.Model = Backbone.Model.extend({});

Matrim.View = Backbone.Marionette.View.extend({
    onBeforeRender: function () {
        if (!_.isFunction(this.template)) {
            this.template = Handlebars.compile($(this.template).html());
        }
    },
    /**
     * ========================================================================
     * ========================================================================
     * === FUNCIONES PARA MODELO ==============================================
     * ========================================================================
     * ========================================================================
     */
    setModelVal: function (modelAttr, uiElement) {
        var uiVal = this.getUIVal(uiElement);

        this.model.set(modelAttr, uiVal);
    },
    setModelFloatVal: function (modelAttr, uiElement, decimals = 2) {
        var uiVal = this.getUIFloatVal(uiElement, decimals);

        this.model.set(modelAttr, uiVal);
    },
    setUIVal: function (uiElement, valor) {
        this.getUI(uiElement).val(valor);
    },
    /**
     * ========================================================================
     * ========================================================================
     * === FUNCIONES PARA UI ==================================================
     * ========================================================================
     * ========================================================================
     */
    getUIVal: function (uiElement) {
        return this.getUI(uiElement).val();
    },
    getUIFloatVal: function (uiElement, decimals = 2) {
        return Matrim.convertirDecimal2(this.getUI(uiElement).val(), decimals);
    },
    setUIVal: function (uiElement, valor) {
        this.getUI(uiElement).val(valor);
    },
    setUIText: function (uiElement, valor) {
        this.getUI(uiElement).text(valor);
    },
    setUIFloatVal: function (uiElement, valor, decimales = 2) {
        var numStr = Matrim.convertirDecimal(valor, decimales);
        this.getUI(uiElement).val(numStr);
    },
    setUIFloatText: function (uiElement, valor, decimales = 2) {
        var numStr = Matrim.convertirDecimal(valor, decimales);
        this.getUI(uiElement).text(numStr);
    },
    setUIFloatValAndText: function (uiElementBase, valor, decimales = 2) {
        var txt = 'txt'+uiElementBase;
        var lbl = 'lbl'+uiElementBase;

        this.setUIFloatVal(txt, valor, decimales);
        this.setUIFloatText(lbl, valor, decimales);
    },
});

Matrim.Collection = Backbone.Collection.extend({});
Matrim.CollectionView = Backbone.Marionette.CollectionView.extend({});
