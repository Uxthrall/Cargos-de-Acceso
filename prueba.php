<select id="mySelect" onchange="myFunction()">
<option value="Audi">Audi
<option value="BMW">BMW
<option value="Mercedes">Mercedes
<option value="Volvo">Volvo
</select>



<script>
function myFunction() {
    var x = document.getElementById("mySelect").value;
    alert(x);
}
</script>
