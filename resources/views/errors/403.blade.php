<!DOCTYPE html> 
<html> 
<head> <title>403 Forbidden.</title> 
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css"> 

<style> 
    html, body { 
       height: 100%; 
    }
 
    body { 
       margin: 0; 
       padding: 0; 
       width: 100%; 
       color: #B0BEC5; 
       display: table; 
       font-weight: 100; 
       font-family: 'Lato'; 
    } 

    .container { 
       text-align: center; 
       display: table-cell; 
       vertical-align: middle; 
    }
 
    .content { 
       text-align: center; 
       display: inline-block; 
    }
 
    .title { 
       font-size: 72px; 
       margin-bottom: 40px; 
    } 
</style> 
</head> 
<body> 
    <div class="container"> 
        <div class="content"> 
            <div class="title">403 Forbidden.</div> 
            <h5>Halaman Akan kembali ke Route (/) dalam <span id="detik">5</span> detik</h5>
        </div> 
    </div> 
</body> 

<script src={{ url("/plugins/jquery/jquery-1.9.1.min.js") }}></script>
<script>

   $(document).ready(function() {

      setInterval(() => {
         var detik = Number( $('#detik').html() );
         if (detik == 0 ) {
            window.location.href = "/";
         }

         if (detik > 0) {
            $('#detik').html( detik-1 );   
         }
      }, 1000);

   });

</script>
</html>