/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(){
    
    //Closing all message box on click cross button
    $('a.close').click(function(){
        $(this).parent().fadeOut(500);
    });
    
    //initiating the datepickers
    if($('.datepicker').length>0){
        $('.datepicker').datepicker();
    }
    
    //initiating form validation
    if($('.validationForm').length>0){
//        $('.validationForm').validate();
    }
    
    //sort the sortable tables
    if($('table.sortable').length>0){
        $('table.sortable').tablesorter();
    }
});