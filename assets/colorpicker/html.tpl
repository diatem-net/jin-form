<label for="%name%">%label%</label><input class="%class%" placeholder="%placeholder%" type="text" name="%name%" value="%value%" %attributes%>%error%

<script language="javascript">
var picker = new CP(document.querySelector('input[name=%name%]'));
picker.on("change", function(color) {
  this.target.value = '#' + color;
});
</script>

