<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr">

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Coverage Map</title> 
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        
        <link href="img/zol_logo_broadband.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
        <link href="img/zol_logo_broadband.png" rel="icon" type="image/vnd.microsoft.icon" />
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />
        
        <script
			  src="https://code.jquery.com/jquery-3.1.0.min.js"
			  integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="
			  crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src=" //maps.googleapis.com/maps/api/js?sensor=false&libraries=geometry,places&key=AIzaSyBZnzVQqVIfhwesx0VyutEaZl-M8w0HHdQ"></script>
        <script src="js/ubilabs-geo/jquery.geocomplete.min.js"></script>
        <script src="js/coveragebtns.js"></script>
        <script src="js/restclient.js"></script>
        <script src="js/script.js"></script>
    </head>
    <body>
        <header>
            <section class="container">
                <div class="row">
                    <h3 class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><img src="img/logo.png" alt="ZOL Zimbabwe" /><span>Coverage Map</span></h3>
                    <div class="searchBox col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="search-div">
                            <input name="search" id="search" type="text" class="textbox searchInput" placeholder="Location e.g Avondale" value=""
                               onchange="setTimeout(search, 1000);" />
                            <button id="btnSearch" type="button" class="btn-search"><i class="fa fa-search"></i></button>
                        </div>                    
                    </div>
                </div>                
            </section>
        </header>
        <section class="main_content">
        
       
    