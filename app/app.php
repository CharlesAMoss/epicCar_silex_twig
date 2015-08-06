<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/car.php";

  session_start();
  $porsche = new Car("2014 Porsche 911", 114991, 7864, "/img/porsche.jpg");
    if (empty($_SESSION['list_of_cars'])) {
        $_SESSION['list_of_cars'] = array();
        $porsche->save();
    }


  $app = new Silex\Application();
  $app['debug'] = true;
  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  $app->get("/", function() use ($app){

      return $app['twig']->render('home.html.twig');

  });


  $app->get("/list_car", function() use ($app) {

    return $app['twig']->render('add-car.html.twig');

  });




  $app->post("/seller_confirm", function() use ($app) {

    $car = new Car($_POST['seller_make'], $_POST['seller_price'], $_POST['seller_miles'], $_POST['seller_picture']);
    $car->save();

    return $app['twig']->render('seller-confirm.html.twig', array('newcar' => $car));

  });


  $app->get("/car_search", function() use ($app) {

    return $app['twig']->render('dealership.html.twig');

  });

  $app->get("/search_results", function() use ($app) {


    //$porsche = new Car("2014 Porsche 911", 114991, 7864, "http://files3.porsche.com/filestore.aspx/normal.jpg?pool=multimedia&type=video&id=981-bo-youtube-ebal&lang=en-us&filetype=normal&version=3b6c2a73-3fad-11e3-bd76-001a64c55f5c");
    //http://www.wellclean.com/wp-content/themes/artgallery_3.0/images/car3.png
    //$ford = new Car("2011 Ford F450", 55995, 14241, "/img/ford.jpg");
    //$lexus = new Car("2013 Lexus RX 350", 44700, 20000, "/img/lexus.jpg");
    //$mercedes = new Car("Mercedes Benz CLS550", 39900, 37979, "/img/cls550.jpg");
    //$mercedes->setPrice(35000.925);
    //$porsche->setPrice("hotdog");
    $user_price = $_GET["user_price"];
    $user_miles = $_GET["user_miles"];


    //$cars = array($porsche, $ford, $lexus, $mercedes);
    //array_push($cars, $_SESSION['list_of_cars']);

    $cars = Car::getAll();

    $arrayOfCars = searchCars($cars, $user_price, $user_miles);
    $whatToReturn = "";
    if (empty($arrayOfCars)) {
      $whatToReturn = '<p>No cars match your search.</p><br /><a href="/">Refine your search</a>';
    }
    else {
      foreach ($arrayOfCars as $specific_car) {
        $car_price = $specific_car->getPrice();
        $car_make = $specific_car->getMake();
        $car_miles = $specific_car->getMiles();
        $car_picture = $specific_car->getPicture();
        $whatToReturn .= "<div><img src='$car_picture' /></div>
              <p>$car_make</p>
              <p>$car_miles miles</p>
              <p>$$car_price</p>
            ";
      }
    }

    // creates the array with one index: the key value of the String $whatToReturn
    return $app['twig']->render('search-results.html.twig', array('whatToReturn' => $whatToReturn));

  });

  $app->get("/all", function() use ($app) {

    return $app['twig']->render('all.html.twig', array('cars' => Car::getAll()));

  });

  $app->get("/delete_all", function() use ($app) {

    Car::deleteAll();
    return $app['twig']->render('delete-all.html.twig');

  });

    return $app;
?>
