/*
 * The jquery static functions for the Florida Contact Manager Importer
 * 
 * Static functions mainly for ajax operability
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
 * @package     jquery.ui.ContactManager.js
 * @version     0.1
 * @author      James Jones (stpetegreens.org)
 * @since       04/11/2012
 * @license     GPL v2
 * @category    FloridaVotersManager
 * @copyright   2011-2012 James Jones, all rights reserved.
 */
(function($) {
    $.Importer = {
        importRawData: function(callback) {
            $.ajax({
                title: "Please wait...",
                data: {
                    method: "importRawData"
                },
                success: callback
            });            
        }
        
    };
})(jQuery);


