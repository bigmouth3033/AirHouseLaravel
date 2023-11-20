<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Document</title>
</head>
<body>

     @if(session('token'))
          <p>Welcome to the website!</p>
          @foreach ($data as $item)
         <p>{{$item->id}}</p>
         <p>{{$item->name}}</p>
         <p>{{$item->icon_image}}</p>
         <p>{{$item->create_at}}</p>
         <img src="{{$item->icon_image}}" alt="">
         <br><br>
     @endforeach
     @else
          <p>Please log in to access the content.</p>
     @endif

</body>
</html>