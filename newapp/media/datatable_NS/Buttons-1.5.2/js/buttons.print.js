/*!
 * Print button for Buttons and DataTables.
 * 2016 SpryMedia Ltd - datatables.net/license
 */

(function( factory ){
	if ( typeof define === 'function' && define.amd ) {
		// AMD
		define( ['jquery', 'datatables.net', 'datatables.net-buttons'], function ( $ ) {
			return factory( $, window, document );
		} );
	}
	else if ( typeof exports === 'object' ) {
		// CommonJS
		module.exports = function (root, $) {
			if ( ! root ) {
				root = window;
			}

			if ( ! $ || ! $.fn.dataTable ) {
				$ = require('datatables.net')(root, $).$;
			}

			if ( ! $.fn.dataTable.Buttons ) {
				require('datatables.net-buttons')(root, $);
			}

			return factory( $, root, root.document );
		};
	}
	else {
		// Browser
		factory( jQuery, window, document );
	}
}(function( $, window, document, undefined ) {
'use strict';
var DataTable = $.fn.dataTable;


var _link = document.createElement( 'a' );

/**
 * Clone link and style tags, taking into account the need to change the source
 * path.
 *
 * @param  {node}     el Element to convert
 */
var _styleToAbs = function( el ) {
	var url;
	var clone = $(el).clone()[0];
	var linkHost;

	if ( clone.nodeName.toLowerCase() === 'link' ) {
		clone.href = _relToAbs( clone.href );
	}

	return clone.outerHTML;
};

/**
 * Convert a URL from a relative to an absolute address so it will work
 * correctly in the popup window which has no base URL.
 *
 * @param  {string} href URL
 */
var _relToAbs = function( href ) {
	// Assign to a link on the original page so the browser will do all the
	// hard work of figuring out where the file actually is
	_link.href = href;
	var linkHost = _link.host;

	// IE doesn't have a trailing slash on the host
	// Chrome has it on the pathname
	if ( linkHost.indexOf('/') === -1 && _link.pathname.indexOf('/') !== 0) {
		linkHost += '/';
	}

	return _link.protocol+"//"+linkHost+_link.pathname+_link.search;
};


DataTable.ext.buttons.print = {
	className: 'buttons-print',

	text: function ( dt ) {
		return dt.i18n( 'buttons.print', 'Print' );
	},

	action: function ( e, dt, button, config ) {
		var data = dt.buttons.exportData(
			$.extend( {decodeEntities: false}, config.exportOptions ) // XSS protection
		);
		/* Caso o footer seja true e houver footer dinamicamente adicionado
		*	 Suporta apenas footer de uma linha
		*/
		if ( !data.footer && config.footer ) {
			data.footer = [];
			var linha = [];
			var footerColumnns = dt.header()[0].parentNode.children[2].children[0].children;
			for ( let i = 0; i < footerColumnns.length; i++ ) {
				let coluna = dt.header()[0].parentNode.children[2].children[0].children[i];
				let colspan = coluna.getAttribute("colspan");

				if ( colspan != null ) {
					colspan = parseInt(colspan);
					linha.push(coluna.innerHTML);

					while ( colspan > 1 ) {
						linha.push("_colspan_");
						colspan--;
					}

				} else {
					linha.push(coluna.innerHTML);
				}
			}
			data.footer.push(linha);
		}
		var exportInfo = dt.buttons.exportInfo( config );
		var columnClasses = dt
			.columns( config.exportOptions.columns )
			.flatten()
			.map( function (idx) {
				return dt.settings()[0].aoColumns[dt.column(idx).index()].sClass;
			} )
			.toArray();

		var addRow = function ( d, tag ) {
			var str = '<tr>';

			for ( var i=0, ien=d.length ; i<ien ; i++ ) {
				// null and undefined aren't useful in the print output
				var dataOut = d[i] === null || d[i] === undefined ?
					'' :
					d[i];
				var classAttr = columnClasses[i] ?
					'class="'+columnClasses[i]+'"' :
					'';

				str += '<'+tag+' '+classAttr+'>'+dataOut+'</'+tag+'>';
			}

			return str + '</tr>';
		};

		// Construct a table for printing
		var html = '<table class="'+dt.table().node().className+'">';

		if ( config.header ) {
			/* ----- BEGIN added/edited Code ----- */
			//html += '<thead>'+ addRow( data.header, 'th' ) +'</thead>';
			html += "<thead>";

			//for each header row
			for ( var i=0, ien=data.header.length ; i<ien ; i++ ) {
            	html += "<tr>";
              
              	//for each column (cell) in the row
              	for(var j=0; j<data.header[i].length; j++) {
	                //look for a non-colspan/rowspan cell
	                if(data.header[i][j] != "_colspan_" && data.header[i][j] != "_rowspan_") {
	                	var startRow = i;
		                var startCol = j;
		                var endRow = i;
		                var endCol = j;
	                                       
	                    //lookahead
	                    if(j+1 < data.header[i].length){ 
	                        if(data.header[i][j+1] == "_colspan_") { //is the cell next to a colspan?
	                          
	                          startCol = j;
	                          endCol = j+1;
	  
	                          //get to the last column in the colspan
	                          while(endCol < data.header[i].length && data.header[i][endCol] == "_colspan_") {
	                            endCol++;
	                          }
	                          endCol--;
	                        }
	                     }
	                    
	                    if(i+1 < data.header.length) {
	                        if(data.header[i+1][j] == "_rowspan_") //is the cell above a rowspan?
	                        {  
	                          
	                          startRow = i;
	                          endRow = i+1;
	  
	                          //get to the last row in the rowspan
	                          while(endRow < data.header.length - 1 && data.header[endRow][j] == "_rowspan_") {
	                            endRow++;
	                          }
	                        }
	                    }
	                    
		                //create and store merged ranges
		                //if endCol or endRow show movement
		                   
		                var cspan = endCol - startCol + 1;
		                var rspan = endRow - startRow + 1;  

		                var classAttr = columnClasses[j] ?
						'class="'+columnClasses[j]+'"' :
						'';
	              

	                	html += '<th colspan="' + cspan + '" rowspan="' + rspan + '" ' +classAttr+'>' + data.header[i][j] + '</th>'
                	}
              	}
				
				html += "</tr>";
            }               
            
            html += "</thead>";
            /* ----- END added/edited Code ----- */
		}

		html += '<tbody>';
		for ( var i=0, ien=data.body.length ; i<ien ; i++ ) {
			html += addRow( data.body[i], 'td' );
		}
		html += '</tbody>';

		if ( config.footer && data.footer ) {
			/* ----- BEGIN added/edited Code ----- */
			//html += '<tfoot>'+ addRow( data.footer, 'th' ) +'</tfoot>';
			html += "<tfoot>";

			//for each footer row
			for ( var i=0, ien=data.footer.length ; i<ien ; i++ ) {
            	html += "<tr>";
              
              	//for each column (cell) in the row
              	for(var j=0; j<data.footer[i].length; j++) {
	                //look for a non-colspan/rowspan cell
	                if(data.footer[i][j] != "_colspan_" && data.footer[i][j] != "_rowspan_") {
	                	var startRow = i;
		                var startCol = j;
		                var endRow = i;
		                var endCol = j;
	                                       
	                    //lookahead
	                    if(j+1 < data.footer[i].length){ 
	                        if(data.footer[i][j+1] == "_colspan_") { //is the cell next to a colspan?
	                          
	                          startCol = j;
	                          endCol = j+1;
	  
	                          //get to the last column in the colspan
	                          while(endCol < data.footer[i].length && data.footer[i][endCol] == "_colspan_") {
	                            endCol++;
	                          }
	                          endCol--;
	                        }
	                     }
	                    
	                    if(i+1 < data.footer.length) {
	                        if(data.footer[i+1][j] == "_rowspan_") //is the cell above a rowspan?
	                        {  
	                          
	                          startRow = i;
	                          endRow = i+1;
	  
	                          //get to the last row in the rowspan
	                          while(endRow < data.footer.length - 1 && data.footer[endRow][j] == "_rowspan_") {
	                            endRow++;
	                          }
	                        }
	                    }
	                    
		                //create and store merged ranges
		                //if endCol or endRow show movement
		                   
		                var cspan = endCol - startCol + 1;
		                var rspan = endRow - startRow + 1;  

		                var classAttr = columnClasses[j] ?
						'class="'+columnClasses[j]+'"' :
						'';
	              

	                	html += '<th colspan="' + cspan + '" rowspan="' + rspan + '" ' +classAttr+'>' + data.footer[i][j] + '</th>'
                	}
              	}
				
				html += "</tr>";
            }               
            
            html += "</tfoot>";
			/* ----- END added/edited Code ----- */
		}
		html += '</table>';
		// Open a new window for the printable table
		var win = window.open( '', '' );
		win.document.close();

		// Inject the title and also a copy of the style and link tags from this
		// document so the table can retain its base styling. Note that we have
		// to use string manipulation as IE won't allow elements to be created
		// in the host document and then appended to the new window.
		var head = '<title>'+exportInfo.title+'</title>';
		$('style, link').each( function () {
			head += _styleToAbs( this );
		} );

		try {
			win.document.head.innerHTML = head; // Work around for Edge
		}
		catch (e) {
			$(win.document.head).html( head ); // Old IE
		}

		// Inject the table and other surrounding information
		win.document.body.innerHTML =
			'<h1>'+exportInfo.title+'</h1>'+
			'<div>'+(exportInfo.messageTop || '')+'</div>'+
			html+
			'<div>'+(exportInfo.messageBottom || '')+'</div>';

		$(win.document.body).addClass('dt-print-view');

		$('img', win.document.body).each( function ( i, img ) {
			img.setAttribute( 'src', _relToAbs( img.getAttribute('src') ) );
		} );

		if ( config.customize ) {
			config.customize( win, config, dt );
		}

		// Allow stylesheets time to load
		var autoPrint = function () {
			if ( config.autoPrint ) {
				win.print(); // blocking - so close will not
				win.close(); // execute until this is done
			}
		};

		if ( navigator.userAgent.match(/Trident\/\d.\d/) ) { // IE needs to call this without a setTimeout
			autoPrint();
		}
		else {
			win.setTimeout( autoPrint, 1000 );
		}
	},

	title: '*',

	messageTop: '*',

	messageBottom: '*',

	exportOptions: {},

	header: true,

	footer: false,

	autoPrint: true,

	customize: null
};


return DataTable.Buttons;
}));
