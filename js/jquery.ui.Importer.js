/*
 * The jquery Plugin interface for the Florida Contact Manager Importer
 * 
 * Plugin provides all the interface rendering, events and behavior for the Importer
 * 
 * CONTACT: jamjon3@stpetegreens.org
 * 
 * LICENSE: This source file is free software, under the GPL v2 license, as supplied with this software.
 *
 * This source file is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.
 *
 * For details please refer to: https://stpetgreens.org
 * 
 * @package     jquery.ui.Importer.js
 * @version     0.1
 * @author      James Jones (stpetegreens.org)
 * @since       04/11/2012
 * @license     GPL v2
 * @category    FloridaVotersManager
 * @copyright   2011-2012 James Jones, all rights reserved.
 */
(function($) {
    $.widget("ui.Importer", {
        options: { 
            importerFields: {
                fields: {
                    importButton: {
                        input: $('<button />').button({
                                text: true,
                                label: "Import",
                                disabled: false
                            }),
                        label: "Start Importing Raw Data"                        
                    }
                }
            }            
        },
        _create: function() {
            var self = this,
            o = self.options,
            el = self.element,
            im = $(self.importerRoot = $('<div />',{'id': 'importer'}))
                .appendTo(el)
                .append(self.importerForm());
        },
        importerForm: function() {
            var self = this,
            el = self.element,
            o = self.options;
            return $('<div />')
            .addClass('ui-widget-content')
            .width('100%')
            .append(
                $('<fieldset />')
                .addClass('ui-widget-content')
                .append(
                    $('<legend />')
                    .html("Import Functions")
                    .addClass('ui-state-default ui-widget-header ui-corner-all')
                )
                .append(
                    $('<table />')
                    .append(
                        $('<tbody />')
                        .append(
                            $('<tr />')
                            .append(
                                $('<td />')
                                .css({
                                    "vertical-align": "top"
                                })
                                .append(self.buildImporterTable())
                            )
                        )
                    )
                )
            );
        },
        buildImporterTable: function() {
            var self = this,
            el = self.element,
            o = self.options,
            fields = o.importerFields.fields;
            return $('<table />')
            .append(
                $('<tr />')
                .append(
                    $('<th />',{
                        "colspan": "2"
                    })
                    .html("Importer")
                    .addClass('ui-state-default ui-widget-header ui-corner-all')
                )
            ).each(function(index,importerTable) {
                $.each(fields,function(key,value) {
                    $(importerTable).append(
                        $('<tr />')
                        .append(
                            $('<td />')
                            .append(
                                $('<label />',{
                                    "for": key
                                })
                                .html(value.label)
                            )
                        )
                        .append(
                            $('<td />')
                            .append(
                                value.input
                                .prop({
                                    "id": key
                                }).each(function(index,input) {
                                    switch(key) {
                                        case "importButton":
                                            $(input).data({
                                                importDate: $('<input />'),
                                                voterZip: $('<input />',{
                                                    "type": "file",
                                                    "name": "voterZip",
                                                    "id": "voterZip"
                                                }),
                                                historyZip: $('<input />',{
                                                    "type": "file",
                                                    "name": "historyZip",
                                                    "id": "historyZip"
                                                })
                                            }).click(function() {
                                                if(typeof $(this).data('hash') === "undefined") {
                                                    $('<div />')
                                                    .data({
                                                        importDate: $('<input />'),
                                                        voterZip: $('<input />',{
                                                            "type": "file",
                                                            "name": "voterZip",
                                                            "id": "voterZip"
                                                        }),
                                                        historyZip: $('<input />',{
                                                            "type": "file",
                                                            "name": "historyZip",
                                                            "id": "historyZip"
                                                        })
                                                    })
                                                    .addClass('ui-state-default ui-widget-content')
                                                    .append(
                                                        $('<p />')
                                                        .css({
                                                            "text-align": "center",
                                                            "margin-top": "0px",
                                                            "margin-bottom": "0px",
                                                            "padding": "0px"
                                                        })
                                                    )                                                                                                    
                                                    .dialog({                                                     
                                                        autoOpen: true,
                                                        bgiframe: true,
                                                        resizable: false,
                                                        title: 'Add New Contact',
                                                        height:620,
                                                        width:760,
                                                        modal: true,
                                                        zIndex: 3999,
                                                        overlay: {
                                                            backgroundColor: '#000',
                                                            opacity: 0.5
                                                        },
                                                        open: function() {
                                                            $(this).append(
                                                                $(input).data('importDate').datepicker({
                                                                    dateFormat: 'yy-mm-dd',
                                                                    showButtonPanel: true 
                                                                })
                                                            ).append(
                                                                $(input).data('voterZip')
                                                            ).append(
                                                                $(input).data('historyZip')
                                                            );
                                                        },
                                                        buttons: {
                                                            "OK": function() {
                                                                var dialog = $(this);
                                                                $(input).data({
                                                                    hash: {
                                                                        importDate: $(input).data('importDate').detach().val(),
                                                                        voterZip: $(input).data('voterZip').detach().val(),
                                                                        historyZip: $(input).data('historyZip').detach().val()
                                                                    }
                                                                }).click();
                                                                dialog.dialog('close');
                                                                dialog.dialog('destroy');
                                                                dialog.remove();                                                                
                                                            },
                                                            "Cancel": function() {
                                                                $(this).dialog('close');
                                                                $(this).dialog('destroy');
                                                                $(this).remove();                                                                
                                                            }
                                                        }
                                                    });                                                    
                                                } else {
                                                    alert(JSON.stringify($(input).data('hash')));
                                                    $('<form />',{
                                                        
                                                    })
                                                    .append(                                                        
                                                        $(input).data('importDate')
                                                    )
                                                    .append(                                                        
                                                        $(input).data('voterZip')
                                                    )
                                                    .append(                                                        
                                                        $(input).data('historyZip')
                                                    )
                                                    .submit(function() {
                                                        
                                                        $(this).remove();
                                                    })
//                                                    $.Importer.importRawData(function(importRawDataResponse) {
//
//                                                    });
                                                    $(input).removeData('hash');
                                                }
                                            });
                                            break;
                                    }
                                })
                            )
                        )
                    );
                });                    
            });
        }        
        
    });
})(jQuery);

