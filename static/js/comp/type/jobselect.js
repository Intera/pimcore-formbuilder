pimcore.registerNS("Formbuilder.comp.type.jobselect");
Formbuilder.comp.type.jobselect = Class.create(Formbuilder.comp.type.base,{

    type: "jobselect",

    multiOptionStore : null,

    getTypeName: function () {
        return t("jobselect");
    },

    getIconClass: function () {
        return "Formbuilder_icon_multiselect";
    },

    getForm: function($super){

        $super();

        var thisNode = new Ext.form.FieldSet({
            title: t("This node"),
            collapsible: true,
            defaultType: 'textfield',
            items:[{
                xtype: "textfield",
                name: "separator",
                fieldLabel: t("separator"),
                anchor: "100%"
            },
                {
                    xtype: "checkbox",
                    name: "registerInArrayValidator",
                    fieldLabel: t("registerInArrayValidator"),
                    checked:false
                },

                //this.generateMultiOptionsRepeaterField()

            ]
        });

        this.form.add(thisNode);

        return this.form;
    },

    getTranslateForm: function($super){

        $super();

        if(this.datax.multiOptions){

            var values = [];

            for (var i=0;i<this.datax.multiOptions.length;i++){
                values.push([this.datax.multiOptions[i]["value"],this.datax.multiOptions[i]["value"]]);
            };

            this.multiOptionStore = new Ext.data.ArrayStore({
                fields: ["key","label"],
                data : values
            });
        }

        var trans = new Ext.form.FieldSet({
            title: t("jobSelect translation"),
            collapsible: true,
            defaultType: 'textfield',
            items:[
                this.generateLocaleRepeaterField('jobSelect')
            ]
        });

        this.transForm.add(trans);

        return this.transForm;

    }

});