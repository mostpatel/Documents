var oTable;
function trim(str) {
                        str = str.replace(/^\s+/, '');
                        for (var i = str.length - 1; i >= 0; i--) {
                                if (/\S/.test(str.charAt(i))) {
                                        str = str.substring(0, i + 1);
                                        break;
                                }
                        }
                        return str;
                }
 
function dateHeight(dateStr){
	    
        if (trim(dateStr) != '' && dateStr.search("/")!=-1) {
				
                //var frDate = trim(dateStr).split(' ');
                //var frTime = frDate[1].split(':');
               // var frDateParts = frDate[0].split('/');
			   
			   var frDateParts = dateStr.split('/');
                var day = frDateParts[0] * 60 * 24;
                var month = frDateParts[1] * 60 * 24 * 31;
                var year = frDateParts[2] * 60 * 24 * 366;
              //  var hour = frTime[0] * 60;
               // var minutes = frTime[1];
                var x = day+month+year;
        } else {
			   
                var x = 99999999999999999; //GoHorse!
        }
        return x;
}
 
                jQuery.fn.dataTableExt.oSort['date-euro-asc'] = function(a, b) {
					 
			
					   if(a!="na" && a!="NA")
					   {
						 var x = dateHeight(a);
					   }
						else
						{
						
						x=9999999999999999999;
						}
                       
					    if(b!="na" && b!="NA")
                        var y = dateHeight(b);
						else
						{
						
						y=99999999999999999999;
						}
						
                        var z = ((x < y) ? -1 : ((x > y) ? 1 : 0));
						
                        return z;
                };
 
                jQuery.fn.dataTableExt.oSort['date-euro-desc'] = function(a, b) {
						
					
					
                         if(a!="na" && a!="NA")
					   {
						 var x = dateHeight(a);
					   }
						else
						{
						
						x=-9999999999999999999999999;
						}
                      
					    if(b!="na" && b!="NA")
                        var y = dateHeight(b);
						else
						{
						y=-9999999999999999999999999;
						}
						
                        var z = ((x < y) ? 1 : ((x > y) ? -1 : 0));
						
                        return z;
                };
 
 
  jQuery.fn.dataTableExt.oSort['date-euro-accounts-asc'] = function(a, b) {
					 
			
					   if(a!="na" && a!="NA")
					   {
						 var x = dateHeight(a);
					   }
						else
						{
						
						x=9999999999999999999;
						}
                       
					    if(b!="na" && b!="NA")
                        var y = dateHeight(b);
						else
						{
						
						y=99999999999999999999;
						}
						
                        var z = ((x < y) ? -1 : ((x > y) ? 1 : 0));
						
                        return z;
                };
 
                jQuery.fn.dataTableExt.oSort['date-euro-accounts-desc'] = function(a, b) {
						
					
					
                      if(a!="na" && a!="NA")
					   {
						 var x = dateHeight(a);
					   }
						else
						{
						
						x=9999999999999999999;
						}
                       
					    if(b!="na" && b!="NA")
                        var y = dateHeight(b);
						else
						{
						
						y=99999999999999999999;
						}
						
                        var z = ((x < y) ? -1 : ((x > y) ? 1 : 0));
						
                        return z;
                };
 

jQuery.fn.dataTableExt.oSort['string-case-asc']  = function(x,y) {
	
	var endSpan=x.indexOf('</span>');
	x=x.slice(27,endSpan);
	
	var endSpan1=y.indexOf('</span>');
	y=y.slice(27,endSpan1);
	var xDotPOs=x.indexOf(".");
	var xPrefix=x.slice(0,xDotPOs);
	var xSuffix=x.substring(xDotPOs+1);
	
	var yDotPOs=y.indexOf(".");
	var yPrefix=y.slice(0,yDotPOs);
	var ySuffix=y.substring(yDotPOs+1);
	
	if(xPrefix==yPrefix)
	{
		return ((parseInt(xSuffix) < parseInt(ySuffix)) ? -1 : ((parseInt(xSuffix) > parseInt(ySuffix)) ?  1 : 0));
		}
	else if(parseInt(xPrefix) < parseInt(yPrefix))
	{
		return -1;
		
		}	
	else if(parseInt(xPrefix) > parseInt(yPrefix))
	{
		return 1;
		
		}		
			
	
	
};

jQuery.fn.dataTableExt.oSort['string-case-desc'] = function(x,y) {
	var endSpan=x.indexOf('</span>');
	x=x.slice(27,endSpan);
	
	var endSpan1=y.indexOf('</span>');
	y=y.slice(27,endSpan1);
	var xDotPOs=x.indexOf(".");
	var xPrefix=x.slice(0,xDotPOs);
	var xSuffix=x.substring(xDotPOs+1);
	
	var yDotPOs=y.indexOf(".");
	var yPrefix=y.slice(0,yDotPOs);
	var ySuffix=y.substring(yDotPOs+1);
	
	if(xPrefix==yPrefix)
	{
		return ((parseInt(xSuffix) < parseInt(ySuffix)) ? 1 : ((parseInt(xSuffix) > parseInt(ySuffix)) ?  -1 : 0));
		}
	else if(parseInt(xPrefix) < parseInt(yPrefix))
	{
		return 1;
		
		}	
	else if(parseInt(xPrefix) > parseInt(yPrefix))
	{
		return -1;
		
		}		
};

			
$(document).ready(function() {
	
	
	
	TableTools.DEFAULTS.aButtons = [ "copy", "xls"];
				 $('#adminContentTable').dataTable({
			"sDom": 'T<"clear">lfrtip',
       		 "oTableTools": {
            "sSwfPath": document.web_root+"js/copy_csv_xls_pdf.swf"
        },		 
             bJQueryUI: false,
			 "sPaginationType": "full_numbers",
			 "bStateSave": true,
			   "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			   "aoColumns": DontSortArray('#adminContentTable')
			  
			    
});

var no_tables =document.no_of_tables;

for(var o=0;o<=no_tables;o++)
{
	TableTools.DEFAULTS.aButtons = [ "copy", "xls"];
				 $('#adminContentTable'+o).dataTable({
			"sDom": 'T<"clear">lfrtip',
       		 "oTableTools": {
            "sSwfPath": document.web_root + "js/copy_csv_xls_pdf.swf"
        },		 
             bJQueryUI: false,
			 "sPaginationType": "full_numbers",
			 "bStateSave": true,
			   "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			   "aoColumns": DontSortArray('#adminContentTable'+o)
			  
			    
});
	
}

});

$(document).ready(function() {
	
	
	
	TableTools.DEFAULTS.aButtons = [ "copy", "xls"];
				 $('#accountContentTable').dataTable({
			"sDom": 'T<"clear">lfrtip',
       		 "oTableTools": {
            "sSwfPath": document.web_root+"js/copy_csv_xls_pdf.swf"
        },		 
		"fnDrawCallback": function ( oSettings ) {
			
			if ( oSettings.bSorted || oSettings.bFiltered )
			{
				for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
				{
					$('td:eq(1)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ).html( i+1 );
				}
			}
		},	
             bJQueryUI: false,
			 "sPaginationType": "full_numbers",
			 "bStateSave": true,
			   "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			   "aoColumns": DontSortArrayAccounts('#accountContentTable'),
			    "aaSorting": DefaultSortArray('#accountContentTable')
			    
});
});
			
$(document).ready(function() {
	TableTools.DEFAULTS.aButtons = [ "copy", "xls"  ];
				 oTable=$('#adminContentReport').dataTable({
					 "sDom": 'T<"clear">lfrtip',
       		 "oTableTools": {
            "sSwfPath": document.web_root + "js/copy_csv_xls_pdf.swf"
        },		 
		"fnDrawCallback": function ( oSettings ) {
			
			if ( oSettings.bSorted || oSettings.bFiltered )
			{
				for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
				{
					$('td:eq(1)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ).html( i+1 );
				}
			}
		},	
        "aaSorting": [[ 1, 'asc' ]],		 
             bJQueryUI: false,
			 "sPaginationType": "full_numbers",
			 "bStateSave": true,
			   "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			   "aoColumns": DontSortArray('#adminContentReport')
			
});

			});		




function DontSortArray(id)
{
	var dontSort = [];
         
 		var i=0;
            $(id+' thead th').each( function () {
                if ( $(this).hasClass( 'no_sort' )) {
                    dontSort.push( { "bSortable": false } );
                } else 
				{
                    if($(this).hasClass( 'date' ))
					{
                        dontSort.push({ "sType": "date-euro"});
                    }
					else if($(this).hasClass( 'numeric' ))
					{
                        dontSort.push({ "sType": "numeric"});
                    }
					else if($(this).hasClass( 'file' ))
					{
                        dontSort.push({ "sType": "string-case"});
                    }
                    else
					{
                        dontSort.push( null );
                    }
                }
				i++;
            } );
			
	return dontSort;
	
	}
	
function DontSortArrayAccounts(id2)
{
	var dontSortAccounts = [];
         
 		var i=0;
            $(id2+' thead th').each( function () {
                if ( $(this).hasClass( 'no_sort' )) {
                    dontSortAccounts.push( { "bSortable": false } );
                } else 
				{
                    if($(this).hasClass( 'date' ))
					{
                        dontSortAccounts.push({ "sType": "date-euro-accounts"});
                    }
					else if($(this).hasClass( 'numeric' ))
					{
                        dontSortAccounts.push({ "sType": "numeric"});
                    }
					else if($(this).hasClass( 'file' ))
					{
                        dontSortAccounts.push({ "sType": "string-case"});
                    }
                    else
					{
                        dontSortAccounts.push( null );
                    }
                }
				i++;
            } );	
	return dontSortAccounts;
	
	}
	
function DefaultSortArray(id2)
{
	var defaultSort = [];
        
 		 var i=0;
            $(id2+' thead th').each( function () {
                
                    if($(this).hasClass( 'default_sort' ))
					{
						var defaultSortColumn = [[i,'desc']];
                        defaultSort.push(defaultSortColumn);
						return false;
                    }
				i++;
            } );			
	return defaultSort;
	
	}		
		