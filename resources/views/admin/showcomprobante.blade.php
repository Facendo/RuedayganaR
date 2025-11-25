<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
    <link rel="stylesheet" href="{{asset('css/cards.css')}}">
    <link rel="stylesheet" href="{{asset('css/forms.css')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Cal+Sans&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&family=Rubik+Mono+One&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/favicon-32x32.png')}}">
    <title>Panel de tickets</title>
    <styles>
        .container{
        width: 100%;
    }
    .imgcomprobante{
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    }

    .comprobanteimg{
    width: 35%;
    height: 70vh;
    
    }
    .imgsmp{
    width:100%;
    }


    </styles>
</head>
<body>
    
        
<br><br>

<div class="container">
    <div class="imgcomprobante">
        <div class="comprobanteimg">
            <img src="{{asset('storage/'.$imagen)}}" class="imgsmp">
        </div>
    </div>
</div>

<br><br>
        
</body>
</html>