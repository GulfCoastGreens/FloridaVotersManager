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
                    uploadButton: {
                        input: $('<button />').button({
                                text: true,
                                label: "Upload and kickoff",
                                disabled: false
                            }),
                        label: "Upload and kickoff import"                        
                    },
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
                                        case "uploadButton":
                                            $(input).data({
                                                voterZip: $('<input />',{
                                                    "type": "file",
                                                    "name": "files[]",
                                                    "id": "voterZip",
                                                    "data-url": "Upload.php",
                                                    "data-sequential-uploads": "true",
                                                    "data-form-data": '{ "script" : "true" }'
                                                }),
                                                voterUploadDone: $('<div />'),
                                                voterProgress: $('<div />'),
                                                voterProgressBar: $('<p />').css({
                                                    "width": "0%",
                                                    "height": "18px",
                                                    "background": "green"
                                                }),
                                                historyZip: $('<input />',{
                                                    "type": "file",
                                                    "name": "historyZip",
                                                    "id": "historyZip"
                                                })
                                            }).click(function() {
                                                if(typeof $(this).data('hash') === "undefined") {
                                                    $('<div />')
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
                                                        title: 'Upload and kickoff import',
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
                                                                $(input).data('voterZip').fileupload({
                                                                    dataType: 'json',
                                                                    add: function(e, data) {
                                                                        data.submit();
                                                                    },
                                                                    progressall: function(e, data) {
                                                                        var progress = parseInt(data.loaded / data.total * 100, 10);
                                                                        $(input).data('voterProgressBar').css({
                                                                            "width": progress+"%"
                                                                        });
                                                                    },
                                                                    done: function(e, data) {
                                                                        $.each(data.result,function(index,file) {
                                                                            $(input).data('voterUploadDone').append(
                                                                                $('<p />').html("Uploading "+file.name+" completed")
                                                                            );
                                                                        });
                                                                    }
                                                                })
                                                            ).append(
                                                                $(input).data('voterUploadDone')
                                                            ).append(
                                                                $(input).data('voterProgress')
                                                                .append(
                                                                    $(input).data('voterProgressBar')
                                                                )
                                                            );
                                                        },
                                                        buttons: {
                                                            "OK": function() {
                                                                var dialog = $(this);
                                                                $(input).data({
                                                                    hash: {
                                                                        // importDate: $(input).data('importDate').detach().val(),
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
                                                    
                                                }
                                            });
                                            break;
                                        case "importButton":
                                            $(input).data({
                                                uploadForm: $('<form />',{
                                                    "id": "uploadRaw",
                                                    "action": "index.php?method=uploadRaw",
                                                    "method": "POST",
                                                    "enctype": "multipart/form-data"
                                                }),
                                                uploadTable: $('<table />',{
                                                    "id": "files"
                                                }),
                                                uploadButton: $('<button />'),
                                                uploadDiv: $('<div />').addClass('file_upload_label').html("Upload files"),
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
//                                                            $(this).append(
//                                                                $(input).data('importDate').datepicker({
//                                                                    dateFormat: 'yy-mm-dd',
//                                                                    showButtonPanel: true 
//                                                                })
//                                                            ).append(
//                                                                $(input).data('voterZip')
//                                                            ).append(
//                                                                $(input).data('historyZip')
//                                                            );
                                                            $(this).append(
                                                                $(input).data('uploadTable')
                                                            ).prepend(
                                                                $(input).data('uploadForm')
                                                                .append(
                                                                    $(input).data('voterZip')
                                                                )
                                                                .append(
                                                                    $(input).data('historyZip')
                                                                )
                                                                .append(
                                                                    $(input).data('uploadButton')
                                                                )
                                                                .append(
                                                                    $(input).data('uploadDiv')
                                                                ).fileupload({
                                                                    uploadTable: $(input).data('uploadTable'),
                                                                    buildUploadRow: function (files, index, handler) {
                                                                        return $('<tr />')
                                                                        .append(
                                                                            $('<td />').html(files[index].name)
                                                                        )
                                                                        .append(
                                                                            $('<td />')
                                                                            .addClass('file_upload_progress')
                                                                            .append($('<div />'))
                                                                        )
                                                                        .append(
                                                                            $('<td />')
                                                                            .addClass('file_upload_cancel')
                                                                            .append(
                                                                                $('<button />',{
                                                                                    "title": "Cancel"
                                                                                })
                                                                                .addClass('ui-state-default ui-corner-all')
                                                                                .append(
                                                                                    $('<span />')
                                                                                    .addClass('ui-icon ui-icon-cancel')
                                                                                    .html("Cancel")
                                                                                )
                                                                            )
                                                                        );
//                                                                        return $('<tr><td>' + files[index].name + '<\/td>' +
//                                                                                '<td class="file_upload_progress"><div><\/div><\/td>' +
//                                                                                '<td class="file_upload_cancel">' +
//                                                                                '<button class="ui-state-default ui-corner-all" title="Cancel">' +
//                                                                                '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
//                                                                                '<\/button><\/td><\/tr>');
                                                                    },
                                                                    buildDownloadRow: function (file, handler) {
                                                                        return $('<tr />')
                                                                        .append(
                                                                            $('<td />')
                                                                            .html(file.name)
                                                                        );
                                                                        // return $('<tr><td>' + file.name + '<\/td><\/tr>');
                                                                    }
                                                                })
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

