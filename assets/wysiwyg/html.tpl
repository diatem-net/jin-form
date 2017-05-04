<label for="%name%">%label%</label><textarea id="%name%" class="%class%" placeholder="%placeholder%" name="%name%" %attributes%>%value%</textarea>%error%
<script type="text/javascript">
  tinymce.init({
    selector: "textarea#%name%"
  });
</script>