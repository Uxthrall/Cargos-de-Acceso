
$(DOCUMENT).ready(function(){
  $("#nueva").click(function(){
    var clonarfila= $("#tablaresponsive").find("#tBody #Tr:last").clone();
    $("table #tBody").append(clonarfila);
  });

  $("#tablaresponsive").on('click', '#eliminalinea', function () {
    var numeroFilas = $("#tablaresponsive tr").length;
    if(numeroFilas>2){
      $(this).closest('tr').remove();
    }
  });
});
