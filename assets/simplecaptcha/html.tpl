<img id="%name%" placeholder="%placeholder%" src="%securimagefile%" alt="CAPTCHA Image">
<input class="%class%" type="text" name="%name%" %attributes%>
<a href="#" onclick="document.getElementById('%name%').src = '%securimagefile%?' + Math.random(); return false">%txtchangecaptcha%</a>%error%