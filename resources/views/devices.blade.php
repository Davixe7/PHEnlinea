<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <form action="devices" method="POST" enctype="multipart/form-data">
    @csrf
    <label>Cargar Archivo XLS</label>
    <input type="file" name="file" id="">
    <button type="submit">Enviar</button>
  </form>
</body>
</html>