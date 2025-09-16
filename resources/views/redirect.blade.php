<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Redirecting…</title>
  </head>
  <body onload="document.forms[0].submit()">
    <form method="post" action="{{ $action }}">
      @foreach($fields as $name => $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
      @endforeach
      <noscript>
        <button type="submit">Continue</button>
      </noscript>
    </form>
  </body>
</html>


